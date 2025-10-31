// Adicionar animações suaves ao carregar a página
document.addEventListener("DOMContentLoaded", () => {
    // Animar cards ao carregar
    const cards = document.querySelectorAll(".projeto-card")
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = "0"
            card.style.transform = "translateY(20px)"
            card.style.transition = "opacity 0.5s ease, transform 0.5s ease"

            setTimeout(() => {
                card.style.opacity = "1"
                card.style.transform = "translateY(0)"
            }, 50)
        }, index * 100)
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
                    // Criar preview se não existir
                    let preview = document.getElementById("preview-imagem")
                    if (!preview) {
                        preview = document.createElement("img")
                        preview.id = "preview-imagem"
                        preview.style.maxWidth = "200px"
                        preview.style.marginTop = "10px"
                        preview.style.borderRadius = "5px"
                        inputImagem.parentElement.appendChild(preview)
                    }
                    preview.src = event.target.result
                }
                reader.readAsDataURL(file)
            }
        })
    }
})
