var truckVue = new Vue ({
    el: '#truckVue',
    data: {
        truckList: [],
        statusDDL: [],
        mode: '',
        truck: { }
    },
    mounted: function () {
        this.mode = 'list';
        this.getAllTruck();
        this.getLookupStatus();
    },
    methods: {
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then(isValid => {
                if (!isValid) return;
                Codebase.blocks('#truckCRUDBlock', 'state_toggle');
                if (this.mode == 'create') {
                    axios.post('/api/post/truck/save', new FormData($('#truckForm')[0])).then(response => {
                        this.backToList();
                    }).catch(e => { this.handleErrors(e); });
                } else if (this.mode == 'edit') {
                    axios.post('/api/post/truck/edit/' + this.truck.hId, new FormData($('#truckForm')[0])).then(response => {
                        this.backToList();
                    }).catch(e => { this.handleErrors(e); });
                } else { }
                Codebase.blocks('#truckCRUDBlock', 'state_toggle');
            });
        },
        getAllTruck: function() {
            Codebase.blocks('#truckListBlock', 'state_toggle');
            axios.get('/api/get/truck/read').then(response => {
                this.truckList = response.data;
                Codebase.blocks('#truckListBlock', 'state_toggle');
            }).catch(e => { this.handleErrors(e); });
        },
        createNew: function() {
            this.mode = 'create';
            this.errors.clear();
            this.truck = this.emptyTruck();
        },
        editSelected: function(idx) {
            this.mode = 'edit';
            this.errors.clear();
            this.truck = this.truckList[idx];
        },
        showSelected: function(idx) {
            this.mode = 'show';
            this.errors.clear();
            this.truck = this.truckList[idx];
        },
        deleteSelected: function(idx) {
            axios.post('/api/post/truck/delete/' + idx).then(response => {
                this.backToList();
            }).catch(e => { this.handleErrors(e); });
        },
        backToList: function() {
            this.mode = 'list';
            this.errors.clear();
            this.getAllTruck();
        },
        emptyTruck: function() {
            return {
                hId: '',
                companyHId: '',
                plate_number: '',
                inspection_date: '',
                driver: '',
                status: '',
                remarks: ''
            }
        },
        getLookupStatus: function() {
            axios.get('/api/get/lookup/byCategory/STATUS').then(
                response => { this.statusDDL = response.data; }
            );
        }
    },
    watch: {
        mode: function() {
            switch (this.mode) {
                case 'create':
                case 'edit':
                case 'show':
                    Codebase.blocks('#truckListBlock', 'close')
                    Codebase.blocks('#truckCRUDBlock', 'open')
                    break;
                case 'list':
                default:
                    Codebase.blocks('#truckListBlock', 'open')
                    Codebase.blocks('#truckCRUDBlock', 'close')
                    break;
            }
        }
    },
    computed: {
        defaultStatus: function() {
            return '';
        }
    }
});