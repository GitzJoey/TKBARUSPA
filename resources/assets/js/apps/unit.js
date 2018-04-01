var unitVue = new Vue ({
    el: '#unitVue',
    data: {
        unit: []
    },
    mounted: function () {
        this.getAllUnit();
    },
    methods: {
        getAllUnit: function() {
            Codebase.blocks('#unitListBlock', 'state_toggle');
            axios.get('/api/get/unit/readAll').then(response => {
                console.log(response.data);
                this.unit = response.data;

                Codebase.blocks('#unitListBlock', 'state_toggle');
            }).catch(e => {
                console.log(e);
            });
        }
    },
    function: {

    }
}); 