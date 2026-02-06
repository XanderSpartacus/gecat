import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["container"]
    static values = {
        index: Number,
        prototype: String
    }

    connect() {
        this.indexValue = this.containerTarget.children.length;
    }

    add() {
        const content = this.prototypeValue.replace(/__name__/g, this.indexValue);
        const div = document.createElement('div');
        // Flexbox simple : Champ à gauche, bouton à droite, alignés en haut
        div.classList.add('d-flex', 'gap-2', 'mb-3', 'align-items-start');

        // Conteneur du widget Symfony (qui contient le label + input)
        const widgetContainer = document.createElement('div');
        widgetContainer.classList.add('flex-grow-1');
        widgetContainer.innerHTML = content;

        // Bouton de suppression
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.classList.add('btn', 'btn-outline-danger');
        removeBtn.innerHTML = '<i class="bi bi-trash"></i>';
        removeBtn.style.marginTop = '0px'; // Ajustement si nécessaire
        removeBtn.onclick = () => div.remove();

        div.appendChild(widgetContainer);
        div.appendChild(removeBtn);

        this.containerTarget.appendChild(div);
        this.indexValue++;
    }
}
