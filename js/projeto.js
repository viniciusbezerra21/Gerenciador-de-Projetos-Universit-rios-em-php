// Adicionar animações suaves ao carregar a página
document.addEventListener("DOMContentLoaded", () => {
    // Animar cards ao carregar com staggered delay
    const cards = document.querySelectorAll(".projeto-card")
    cards.forEach((card, index) => {
      card.style.animationDelay = `${index * 0.1}s`
    })
  
    // Confirmar antes de limpar filtros
    const btnLimpar = document.querySelector(".btn-limpar")
    if (btnLimpar) {
      btnLimpar.addEventListener("click", (e) => {
        const temFiltros = new URLSearchParams(window.location.search).toString()
        if (!temFiltros) {
          e.preventDefault()
        }
      })
    }
  
    // Preview de imagem no formulário de cadastro
    const inputImagem = document.getElementById("imagem")
    if (inputImagem) {
      inputImagem.addEventListener("change", (e) => {
        const file = e.target.files[0]
        if (file) {
          const reader = new FileReader()
          reader.onload = (event) => {
            let preview = document.getElementById("preview-imagem")
            if (!preview) {
              preview = document.createElement("img")
              preview.id = "preview-imagem"
              preview.style.maxWidth = "200px"
              preview.style.marginTop = "10px"
              preview.style.borderRadius = "5px"
              preview.style.animation = "fadeInScale 0.5s ease-out"
              inputImagem.parentElement.appendChild(preview)
            }
            preview.src = event.target.result
          }
          reader.readAsDataURL(file)
        }
      })
    }
  
    const observerOptions = {
      threshold: 0.1,
      rootMargin: "0px 0px -50px 0px",
    }
  
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.animation = "fadeInUp 0.8s ease-out forwards"
          observer.unobserve(entry.target)
        }
      })
    }, observerOptions)
  
    // Observar todas as sections se não estiverem animadas
    document.querySelectorAll("section").forEach((section) => {
      if (section.style.opacity !== "0") {
        observer.observe(section)
      }
    })
  
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener("click", function (e) {
        const href = this.getAttribute("href")
        if (href !== "#") {
          e.preventDefault()
          const target = document.querySelector(href)
          if (target) {
            target.scrollIntoView({
              behavior: "smooth",
              block: "start",
            })
          }
        }
      })
    })
  
    const links = document.querySelectorAll("a[href$='.php']")
    links.forEach((link) => {
      link.addEventListener("click", function (e) {
        const href = this.href
        if (!href.includes("#")) {
          e.preventDefault()
          document.body.style.opacity = "1"
          document.body.style.transition = "opacity 0.3s ease-out"
          document.body.style.opacity = "0"
          setTimeout(() => {
            window.location.href = href
          }, 300)
        }
      })
    })
  
    // Fade in ao entrar na página
    window.addEventListener("pageshow", () => {
      document.body.style.opacity = "0"
      document.body.style.transition = "opacity 0.3s ease-in"
      document.body.style.opacity = "1"
    })
  })
  