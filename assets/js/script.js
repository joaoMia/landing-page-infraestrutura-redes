/**
 * NexusRedes - Script Frontend
 * Gerenciamento do Modal, intl-tel-input, Validações em Tempo Real e AJAX Fetch
 */

document.addEventListener('DOMContentLoaded', () => {
  'use strict';

  // ==========================================
  // 1. Elementos Principais do DOM
  // ==========================================
  const modalOverlay = document.getElementById('leadModal');
  const modalCloseBtn = document.getElementById('modalCloseBtn');
  const openModalBtns = document.querySelectorAll('[data-open-modal]');
  const form = document.getElementById('leadForm');
  const submitBtn = document.getElementById('submitBtn');
  const formAlert = document.getElementById('formAlert');
  const mobileToggle = document.getElementById('mobileToggle');
  const navMenu = document.getElementById('navMenu');

  // Campos do formulário
  const nomeInput = document.getElementById('nome');
  const emailInput = document.getElementById('email');
  const phoneInput = document.getElementById('telefone');
  const ddiHidden = document.getElementById('ddi');
  const telefoneE164Hidden = document.getElementById('telefone_e164');
  const paisIsoHidden = document.getElementById('pais_iso');
  const servicoSelect = document.getElementById('servico_interesse');
  const lgpdCheckbox = document.getElementById('lgpd_consent');

  let lastFocusedElement = null;
  let itiInstance = null;

  // ==========================================
  // 2. Inicialização do intl-tel-input
  // ==========================================
  if (phoneInput && window.intlTelInput) {
    itiInstance = window.intlTelInput(phoneInput, {
      initialCountry: 'br',
      preferredCountries: ['br', 'us', 'pt', 'es', 'ar'],
      separateDialCode: true,
      autoPlaceholder: 'polite',
      formatOnDisplay: true,
      utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js'
    });

    // Atualiza os campos ocultos de DDI e ISO ao trocar de país
    const updateCountryData = () => {
      const countryData = itiInstance.getSelectedCountryData();
      if (countryData && countryData.dialCode) {
        ddiHidden.value = '+' + countryData.dialCode;
        paisIsoHidden.value = countryData.iso2 || 'br';
      }
    };

    phoneInput.addEventListener('countrychange', updateCountryData);
    // Executa a primeira carga
    updateCountryData();
  }

  // ==========================================
  // 3. Controle do Modal (Popup)
  // ==========================================
  const openModal = (serviceName = null) => {
    lastFocusedElement = document.activeElement;

    // Se um botão especificou um serviço, pré-seleciona no select
    if (serviceName && servicoSelect) {
      for (let i = 0; i < servicoSelect.options.length; i++) {
        if (servicoSelect.options[i].value.toLowerCase().includes(serviceName.toLowerCase())) {
          servicoSelect.selectedIndex = i;
          break;
        }
      }
    }

    modalOverlay.classList.add('active');
    modalOverlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    // Foca no primeiro input após a animação
    setTimeout(() => {
      if (nomeInput) nomeInput.focus();
    }, 150);
  };

  const closeModal = () => {
    modalOverlay.classList.remove('active');
    modalOverlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';

    // Restaura o foco para o elemento disparador
    if (lastFocusedElement) {
      lastFocusedElement.focus();
    }
  };

  // Listeners de abertura
  openModalBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const service = btn.getAttribute('data-service');
      openModal(service);
    });
  });

  // Listeners de fechamento
  if (modalCloseBtn) {
    modalCloseBtn.addEventListener('click', closeModal);
  }

  if (modalOverlay) {
    modalOverlay.addEventListener('click', (e) => {
      // Fecha se o clique foi direto no fundo escurecido (overlay)
      if (e.target === modalOverlay) {
        closeModal();
      }
    });
  }

  // Fechar com a tecla ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modalOverlay && modalOverlay.classList.contains('active')) {
      closeModal();
    }
  });

  // ==========================================
  // 4. Menu Mobile
  // ==========================================
  if (mobileToggle && navMenu) {
    mobileToggle.addEventListener('click', () => {
      navMenu.classList.toggle('open');
      const isOpen = navMenu.classList.contains('open');
      mobileToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    // Fecha menu mobile ao clicar em um link
    navMenu.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        navMenu.classList.remove('open');
        mobileToggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  // ==========================================
  // 5. Funções de Validação no Frontend
  // ==========================================
  const setFieldError = (inputEl, errorElId, message) => {
    inputEl.classList.add('is-invalid');
    inputEl.classList.remove('is-valid');
    const errSpan = document.getElementById(errorElId);
    if (errSpan) {
      errSpan.textContent = message;
      errSpan.style.display = 'block';
    }
  };

  const clearFieldError = (inputEl, errorElId) => {
    inputEl.classList.remove('is-invalid');
    const errSpan = document.getElementById(errorElId);
    if (errSpan) {
      errSpan.textContent = '';
    }
  };

  const validateNome = () => {
    const val = nomeInput.value.trim();
    if (val === '') {
      setFieldError(nomeInput, 'error-nome', 'O nome completo é obrigatório.');
      return false;
    }
    if (val.length < 3 || val.length > 100) {
      setFieldError(nomeInput, 'error-nome', 'O nome deve conter entre 3 e 100 caracteres.');
      return false;
    }
    const words = val.split(/\s+/).filter(w => w.length > 0);
    if (words.length < 2) {
      setFieldError(nomeInput, 'error-nome', 'Por favor, informe seu nome e sobrenome.');
      return false;
    }
    clearFieldError(nomeInput, 'error-nome');
    nomeInput.classList.add('is-valid');
    return true;
  };

  const validateEmail = () => {
    const val = emailInput.value.trim();
    if (val === '') {
      setFieldError(emailInput, 'error-email', 'O e-mail é obrigatório.');
      return false;
    }
    // Regex RFC 5322 simplificada e robusta
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
    if (!emailRegex.test(val)) {
      setFieldError(emailInput, 'error-email', 'Informe um endereço de e-mail válido (ex: seu@empresa.com.br).');
      return false;
    }
    clearFieldError(emailInput, 'error-email');
    emailInput.classList.add('is-valid');
    return true;
  };

  const validateTelefone = () => {
    const val = phoneInput.value.trim();
    if (val === '') {
      setFieldError(phoneInput, 'error-telefone', 'O telefone é obrigatório.');
      return false;
    }

    if (itiInstance) {
      if (!itiInstance.isValidNumber()) {
        const countryData = itiInstance.getSelectedCountryData();
        const msg = countryData && countryData.iso2 === 'br'
          ? 'Número de telefone brasileiro inválido. Inclua o DDD (ex: 11 98888-7777).'
          : 'Número de telefone inválido para o país selecionado.';
        setFieldError(phoneInput, 'error-telefone', msg);
        return false;
      }
      
      // Obter formato padronizado E.164 da biblioteca
      const e164Number = itiInstance.getNumber();
      telefoneE164Hidden.value = e164Number;
      
      const countryData = itiInstance.getSelectedCountryData();
      if (countryData && countryData.dialCode) {
        ddiHidden.value = '+' + countryData.dialCode;
      }
    } else {
      // Fallback sem intl-tel-input
      const digits = val.replace(/\D/g, '');
      if (digits.length < 8 || digits.length > 15) {
        setFieldError(phoneInput, 'error-telefone', 'O telefone informado é inválido.');
        return false;
      }
      telefoneE164Hidden.value = (ddiHidden.value || '+55') + digits;
    }

    clearFieldError(phoneInput, 'error-telefone');
    phoneInput.classList.add('is-valid');
    return true;
  };

  const validateLgpd = () => {
    if (!lgpdCheckbox.checked) {
      setFieldError(lgpdCheckbox, 'error-lgpd', 'Você deve aceitar os termos de contato para prosseguir.');
      return false;
    }
    clearFieldError(lgpdCheckbox, 'error-lgpd');
    return true;
  };

  // Limpeza de erros em tempo real ao digitar
  if (nomeInput) {
    nomeInput.addEventListener('input', () => clearFieldError(nomeInput, 'error-nome'));
    nomeInput.addEventListener('blur', validateNome);
  }

  if (emailInput) {
    emailInput.addEventListener('input', () => clearFieldError(emailInput, 'error-email'));
    emailInput.addEventListener('blur', validateEmail);
  }

  if (phoneInput) {
    phoneInput.addEventListener('input', () => clearFieldError(phoneInput, 'error-telefone'));
    phoneInput.addEventListener('blur', validateTelefone);
  }

  if (lgpdCheckbox) {
    lgpdCheckbox.addEventListener('change', () => clearFieldError(lgpdCheckbox, 'error-lgpd'));
  }

  // ==========================================
  // 6. Submissão via AJAX com Fetch API
  // ==========================================
  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      // Esconder alerta global anterior
      formAlert.className = 'form-alert';
      formAlert.style.display = 'none';

      // Executa todas as validações de frontend
      const isNomeValid = validateNome();
      const isEmailValid = validateEmail();
      const isPhoneValid = validateTelefone();
      const isLgpdValid = validateLgpd();

      if (!isNomeValid || !isEmailValid || !isPhoneValid || !isLgpdValid) {
        formAlert.className = 'form-alert alert-error';
        formAlert.innerHTML = '<strong>Atenção:</strong> Por favor, verifique os campos destacados em vermelho.';
        formAlert.style.display = 'flex';
        return;
      }

      // Preparar payload do FormData
      const formData = new FormData(form);

      // Estado de Carregamento no Botão
      submitBtn.classList.add('is-loading');
      submitBtn.disabled = true;
      submitBtn.setAttribute('aria-busy', 'true');
      const btnText = submitBtn.querySelector('.btn-text');
      const originalText = btnText ? btnText.textContent : 'Enviar';
      if (btnText) btnText.textContent = 'Enviando...';

      try {
        const response = await fetch('api/cadastrar.php', {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        const data = await response.json().catch(() => ({
          success: false,
          message: 'Falha ao interpretar a resposta do servidor.'
        }));

        if (response.ok && data.success) {
          // Sucesso: feedback visual antes de redirecionar
          formAlert.className = 'form-alert alert-success';
          formAlert.innerHTML = `<strong>Sucesso!</strong> ${data.message || 'Cadastro realizado!'}`;
          formAlert.style.display = 'flex';

          if (btnText) btnText.textContent = 'Concluído!';

          // Redirecionamento após 750ms para confirmação
          setTimeout(() => {
            window.location.href = data.redirect || 'sucesso.php';
          }, 750);
        } else {
          // Erro retornado pelo backend
          formAlert.className = 'form-alert alert-error';
          formAlert.innerHTML = `<strong>Erro:</strong> ${data.message || 'Não foi possível concluir sua inscrição.'}`;
          formAlert.style.display = 'flex';

          // Mapear erros específicos em cada campo
          if (data.errors && typeof data.errors === 'object') {
            if (data.errors.nome) setFieldError(nomeInput, 'error-nome', data.errors.nome);
            if (data.errors.email) setFieldError(emailInput, 'error-email', data.errors.email);
            if (data.errors.telefone) setFieldError(phoneInput, 'error-telefone', data.errors.telefone);
            if (data.errors.lgpd_consent) setFieldError(lgpdCheckbox, 'error-lgpd', data.errors.lgpd_consent);
          }

          // Restaurar botão
          submitBtn.classList.remove('is-loading');
          submitBtn.disabled = false;
          submitBtn.setAttribute('aria-busy', 'false');
          if (btnText) btnText.textContent = originalText;
        }
      } catch (err) {
        // Erro de rede ou indisponibilidade
        formAlert.className = 'form-alert alert-error';
        formAlert.innerHTML = '<strong>Falha de Conexão:</strong> Não foi possível comunicar com o servidor. Verifique sua conexão e tente novamente.';
        formAlert.style.display = 'flex';

        submitBtn.classList.remove('is-loading');
        submitBtn.disabled = false;
        submitBtn.setAttribute('aria-busy', 'false');
        if (btnText) btnText.textContent = originalText;
      }
    });
  }
});
