(function () {
  "use strict";

  var header = document.querySelector(".site-header");
  var nav = document.querySelector(".nav-main");
  var toggle = document.querySelector(".nav-toggle");
  var hero = document.querySelector(".hero");
  var heroImg = document.querySelector(".hero__parallax");

  function onScroll() {
    if (!header) return;
    var y = window.scrollY || document.documentElement.scrollTop;
    header.classList.toggle("is-scrolled", y > 48);
  }

  window.addEventListener("scroll", onScroll, { passive: true });
  onScroll();

  if (toggle && nav) {
    toggle.addEventListener("click", function () {
      var open = nav.classList.toggle("is-open");
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
    });

    nav.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () {
        nav.classList.remove("is-open");
        toggle.setAttribute("aria-expanded", "false");
      });
    });
  }

  if (hero && heroImg && !window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
    window.addEventListener(
      "scroll",
      function () {
        var rect = hero.getBoundingClientRect();
        if (rect.bottom < 0 || rect.top > window.innerHeight) return;
        var p = Math.min(1, Math.max(0, -rect.top / (rect.height * 0.6)));
        heroImg.style.transform = "scale(1.05) translateY(" + p * 24 + "px)";
      },
      { passive: true }
    );
  }

  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  if (!reduceMotion && "IntersectionObserver" in window) {
    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
          }
        });
      },
      { root: null, rootMargin: "0px 0px -8% 0px", threshold: 0.08 }
    );

    document.querySelectorAll(".reveal, .reveal-stagger").forEach(function (el) {
      observer.observe(el);
    });
  } else {
    document.querySelectorAll(".reveal, .reveal-stagger").forEach(function (el) {
      el.classList.add("is-visible");
    });
  }

  var form = document.getElementById("form-contato");
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var nome = form.querySelector('[name="nome"]');
      var email = form.querySelector('[name="email"]');
      var empresa = form.querySelector('[name="empresa"]');
      var mensagem = form.querySelector('[name="mensagem"]');
      var assunto = encodeURIComponent("Contato — Videira Engenharia");
      var corpo = encodeURIComponent(
        "Nome: " +
          (nome && nome.value ? nome.value : "") +
          "\nE-mail: " +
          (email && email.value ? email.value : "") +
          "\nEmpresa: " +
          (empresa && empresa.value ? empresa.value : "") +
          "\n\nMensagem:\n" +
          (mensagem && mensagem.value ? mensagem.value : "")
      );
      var mail = form.getAttribute("data-mailto") || "contato@videira.com.br";
      window.location.href = "mailto:" + mail + "?subject=" + assunto + "&body=" + corpo;
    });
  }
})();
