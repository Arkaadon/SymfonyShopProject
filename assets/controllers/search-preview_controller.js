import { Controller } from 'stimulus';
import { useClickOutside, useDebounce } from 'stimulus-use';

export default class extends Controller {
    static values = {
        url: String,
    }

    static targets = ['result'];
    static debounces = ['search'];

    connect() {
        useClickOutside(this);
        useDebounce(this);
    }

    onSearchInput(event) {
        this.search(event.currentTarget.value);
    }

    async search(query) {
        const params = new URLSearchParams({
            search: query,
            preview: 1,
        });
        const response = await fetch(`${this.urlValue}?${params.toString()}`);
        let responseText = await response.text();
        // console.log(responseText);
        
        // this.resultTarget.innerHTML = responseText;

        document.querySelector('#Search').innerHTML = responseText;
    }

    clickOutside(event) {
        this.resultTarget.innerHTML = '';
    }
}
