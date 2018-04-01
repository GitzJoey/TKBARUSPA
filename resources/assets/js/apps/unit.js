var unitVue = new Vue ({
    el: 'unitVue',
    data: {
        unit: []
    },
    mounted: function () {
        this.getAllUnit();
    },
    methods: {
        getAllUnit: function() {
            Codebase.blocks('#companyListBlock', 'state_toggle');
            axios.get('').then(response => {
                this.unit = response.data;

                Codebase.blocks('#companyListBlock', 'state_toggle');
            }).catch(e => {
                this.errors.push(e);
            });
        }
    },
    function: {

    }
}); 