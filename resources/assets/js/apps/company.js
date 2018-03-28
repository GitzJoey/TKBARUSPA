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
            setTimeout(function(){ Codebase.blocks('#companyListBlock', 'state_toggle'); }, 8000);
        }
    },
    function: {

    }
});