class Filter {


    /**
     *
     * @param {HTMLElement|null} element
     */
    constructor(element){
        if(element === null){
            return;
        }
        this.form = element.querySelector('.js-filter-form');
        this.bindEvents();
    }

    /**
     * Ajoute les comportements
     */
    bindEvents(){

    }

    async  loadURL(url){
        const response = await fetch(url,{
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })

        if(response.status >= 200 && response.status > 300)
        {
            const data = await response.json();
            this.content.innerHTML = data.content;
        }
        else{
            console.error(response);
        }
    }
}