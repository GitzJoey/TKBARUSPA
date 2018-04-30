var truckVue = new Vue ({
    el: '#truckVue',
    data: {
        truckList: [],
        statusDDL: [],
        truckTypeDDL: [],
        mode: '',
        truck: { }
    },
    mounted: function () {
        this.mode = 'list';
        this.getAllTruck();
        this.getLookupStatus();
        this.getLookupTruckType();
    },
    methods: {
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then(isValid => {
                if (!isValid) return;
                this.errors.clear();
                Codebase.blocks('#truckCRUDBlock', 'state_toggle');
                if (this.mode == 'create') {
                    axios.post(route('api.post.truck.save').url(), new FormData($('#truckForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#truckCRUDBlock', 'state_toggle');
                    }).catch(e => {
                        this.handleErrors(e);
                        Codebase.blocks('#truckCRUDBlock', 'state_toggle');
                    });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.truck.edit', this.truck.hId).url(), new FormData($('#truckForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#truckCRUDBlock', 'state_toggle');
                    }).catch(e => {
                        this.handleErrors(e);
                        Codebase.blocks('#truckCRUDBlock', 'state_toggle');
                    });
                } else { }
            });
        },
        getAllTruck: function() {
            Codebase.blocks('#truckListBlock', 'state_toggle');
            axios.get(route('api.get.truck.read').url()).then(response => {
                this.truckList = response.data;
                Codebase.blocks('#truckListBlock', 'state_toggle');
            }).catch(e => {
                this.handleErrors(e);
                Codebase.blocks('#truckListBlock', 'state_toggle');
            });
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
            axios.post(route('api.post.truck.delete', idx).url()).then(response => {
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
                type: '',
                plate_number: '',
                inspection_date: '',
                driver: '',
                status: '',
                remarks: ''
            }
        },
        getLookupStatus: function() {
             axios.get(route('api.get.lookup.bycategory', 'STATUS').url()).then(
                response => { this.statusDDL = response.data; }
            );
        },
        getLookupTruckType: function() {
             axios.get(route('api.get.lookup.bycategory', 'TRUCK_TYPE').url()).then(
                response => { this.truckTypeDDL = response.data; }
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
        defaultPleaseSelect: function() {
            return '';
        },
        flatPickrConfig: function() {
            var conf = document.getElementById("appSettings").value.split('|');

            return {
                enableTime: false,
                dateFormat: conf[1],
                plugins: [new confirmDatePlugin({
                    confirmIcon: "<i class='fa fa-check'></i>",
                    confirmText: ""
                }), new scrollPlugin()],
                minuteIncrement: 15,

            }
        }
    }
});