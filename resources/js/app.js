(function () {
  "use strict";

  var corpo = document.body;

  // Menu lateral no celular
  document.addEventListener("click", function (e) {
    if (!(e.target instanceof Element)) return;
    if (e.target.closest("[data-menu]")) corpo.classList.toggle("menu-aberto");
    else if (e.target.closest("[data-menu-fechar]")) corpo.classList.remove("menu-aberto");
  });

  // Confirmação em diálogo para ações marcadas com data-confirmar
  var dialogo = document.getElementById("confirmar");
  var formPendente = null;
  if (dialogo) {
    document.addEventListener("click", function (e) {
      var gatilho = e.target instanceof Element ? e.target.closest("[data-confirmar]") : null;
      if (!gatilho) return;
      var form = gatilho.form || gatilho.closest("form");
      if (!form) return;
      e.preventDefault();
      formPendente = form;
      dialogo.querySelector("[data-msg]").textContent = gatilho.getAttribute("data-confirmar");
      dialogo.showModal();
    });
    dialogo.addEventListener("close", function () {
      if (dialogo.returnValue === "ok" && formPendente) formPendente.submit();
      formPendente = null;
      dialogo.returnValue = "";
    });
  }

  // Selects com data-autosubmit enviam o formulário ao mudar (status)
  document.addEventListener("change", function (e) {
    if (e.target instanceof HTMLSelectElement && e.target.hasAttribute("data-autosubmit")) {
      if (e.target.form) e.target.form.submit();
    }
  });

  // Máscaras de CPF, CNPJ e telefone
  var mascaras = {
    cpf: function (v) {
      return v.replace(/\D/g, "").slice(0, 11).replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    },
    cnpj: function (v) {
      return v.replace(/\D/g, "").slice(0, 14).replace(/^(\d{2})(\d)/, "$1.$2").replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3").replace(/\.(\d{3})(\d)/, ".$1/$2").replace(/(\d{4})(\d)/, "$1-$2");
    },
    telefone: function (v) {
      var d = v.replace(/\D/g, "").slice(0, 11);
      if (d.length < 3) return d;
      var resto = d.slice(2), corte = d.length > 10 ? 5 : 4;
      return "(" + d.slice(0, 2) + ") " + (resto.length > corte ? resto.slice(0, corte) + "-" + resto.slice(corte) : resto);
    }
  };
  document.addEventListener("input", function (e) {
    var campo = e.target;
    if (campo instanceof HTMLInputElement && mascaras[campo.getAttribute("data-mascara")]) {
      campo.value = mascaras[campo.getAttribute("data-mascara")](campo.value);
    }
  });

  // Agendamento: o select de pets só mostra os pets do cliente escolhido
  var cliente = document.querySelector("[data-filtra-pets]");
  var pet = document.getElementById("pet_id");
  if (cliente && pet) {
    var filtrar = function () {
      Array.prototype.forEach.call(pet.options, function (op) {
        var visivel = !op.value || op.getAttribute("data-tutor") === cliente.value;
        op.hidden = op.disabled = !visivel;
      });
      if (pet.selectedOptions[0] && pet.selectedOptions[0].disabled) pet.value = "";
    };
    cliente.addEventListener("change", filtrar);
    filtrar();
  }

  // Exames: adicionar e remover linhas
  var listaExames = document.getElementById("exames-lista");
  var modelo = document.getElementById("exame-modelo");
  if (listaExames && modelo) {
    document.addEventListener("click", function (e) {
      if (!(e.target instanceof Element)) return;
      if (e.target.closest("[data-add-exame]")) listaExames.appendChild(modelo.content.cloneNode(true));
      var remover = e.target.closest("[data-remover-exame]");
      if (remover && listaExames.children.length > 1) remover.closest(".exame-linha").remove();
    });
  }

  // Filtro AJAX de clientes com debounce e cancelamento
  var form = document.getElementById("filtro-tutores");
  if (form) {
    var container = document.getElementById("lista-tutores");
    var timer = null;
    var controller = null;

    var buscar = function () {
      if (controller) controller.abort();
      controller = new AbortController();

      var params = new URLSearchParams(new FormData(form));
      params.forEach(function (valor, chave) {
        if (!valor.trim()) params.delete(chave);
      });

      if (container) container.style.opacity = "0.5";

      fetch(form.action + "?" + params, {
        headers: { "X-Requested-With": "XMLHttpRequest" },
        signal: controller.signal
      })
        .then(function (res) { return res.text(); })
        .then(function (html) { if (container) container.innerHTML = html; })
        .catch(function (err) { if (err.name !== "AbortError") console.error(err); })
        .finally(function () { if (container) container.style.opacity = "1"; });
    };

    form.addEventListener("input", function () {
      clearTimeout(timer);
      timer = setTimeout(buscar, 300);
    });

    form.addEventListener("submit", function (e) {
      e.preventDefault();
      buscar();
    });

    var limpar = document.getElementById("limpar-filtro");
    if (limpar) {
      limpar.addEventListener("click", function () {
        form.reset();
        buscar();
      });
    }
  }
})();
