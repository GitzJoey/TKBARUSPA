var companyVue = new Vue ({
    el: '#companyVue',
    data: {
        company: []
    },
    mounted: function () {
        this.getAllCompany();
    },
    methods: {
        getAllCompany: function() {
            Codebase.blocks('#companyListBlock', 'state_toggle');
            axios.get('/api/get/company/readAll').then(response => {
                console.log(response.data);
                this.company = response.data;

                Codebase.blocks('#companyListBlock', 'state_toggle');
            }).catch(e => {
                console.log(e);
                this.errors.push(e);
            });
        }
    },
    function: {

    }
});