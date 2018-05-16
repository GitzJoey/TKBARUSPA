var truckMaintenanceVue = new Vue ({
    el: '#truckMaintenanceVue',
    data: {
        truckMaintenanceList: [],
        truckDDL: [],
        maintenanceTypeDDL: [],
        mode: '',
        truckMaintenance: {
            truck: {
                hId: ''
            }
        }
    },
    mounted: function () {
        this.mode = 'list';
        this.getAllTruckMaintenance();
        this.getTruck();
        this.getLookupMaintenanceType();
    },
    methods: {
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then(isValid => {
                if (!isValid) return;
                this.loadingPanel('#truckMaintenanceCRUDBlock', 'TOGGLE');
                if (this.mode == 'create') {
                    axios.post(route('api.post.truck.truck_maintenance.save').url(), new FormData($('#truckMaintenanceForm')[0])).then(response => {
                        this.backToList();
                        this.loadingPanel('#truckMaintenanceCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#truckMaintenanceCRUDBlock', 'TOGGLE');
                    });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.truck.truck_maintenance.edit', this.truckMaintenance.hId).url(), new FormData($('#truckMaintenanceForm')[0])).then(response => {
                        this.backToList();
                        this.loadingPanel('#truckMaintenanceCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#truckMaintenanceCRUDBlock', 'TOGGLE');
                    });
                } else { }
            });
        },
        getAllTruckMaintenance: function() {
            this.loadingPanel('#truckMaintenanceListBlock', 'TOGGLE');
            axios.get(route('api.get.truck.truck_maintenance.read').url()).then(response => {
                this.truckMaintenanceList = response.data;
                this.loadingPanel('#truckMaintenanceListBlock', 'TOGGLE');
            }).catch(e => { this.handleErrors(e); });
        },
        createNew: function() {
            this.mode = 'create';
            this.errors.clear();
            this.truckMaintenance = this.emptyTruckMaintenance();
        },
        editSelected: function(idx) {
            this.mode = 'edit';
            this.errors.clear();
            this.truckMaintenance = this.truckMaintenanceList[idx];
        },
        showSelected: function(idx) {
            this.mode = 'show';
            this.errors.clear();
            this.truckMaintenance = this.truckMaintenanceList[idx];
        },
        deleteSelected: function(idx) {
            axios.post(route('api.post.truck.truck_maintenance.delete', idx).url()).then(response => {
                this.backToList();
            }).catch(e => { this.handleErrors(e); });
        },
        backToList: function() {
            this.mode = 'list';
            this.errors.clear();
            this.getAllTruckMaintenance();
        },
        emptyTruckMaintenance: function() {
            return {
                hId: '',
                companyHId: '',
                truck: {
                    hId: '',
                },
                maintenance_date: new Date(),
                maintenance_type: '',
                cost: 0,
                odometer: 0,
                remarks: ''
            }
        },
        getTruck: function() {
             axios.get(route('api.get.truck.vendor_trucking.all_trucks_maintained_by_company').url()).then(
                response => { this.truckDDL = response.data; }
            );
        },
        getLookupMaintenanceType: function() {
             axios.get(route('api.get.lookup.bycategory', 'TRUCK_MAINTENANCE_TYPE').url()).then(
                response => { this.maintenanceTypeDDL = response.data; }
            );
        }
    },
    watch: {
        mode: function() {
            switch (this.mode) {
                case 'create':
                case 'edit':
                case 'show':
                    this.contentPanel('#truckMaintenanceListBlock', 'CLOSE')
                    this.contentPanel('#truckMaintenanceCRUDBlock', 'OPEN')
                    break;
                case 'list':
                default:
                    this.contentPanel('#truckMaintenanceListBlock', 'OPEN')
                    this.contentPanel('#truckMaintenanceCRUDBlock', 'CLOSE')
                    break;
            }
        }
    },
    computed: {
        defaultPleaseSelect: function() {
            return '';
        },
        currencyConfig: function() {
            var conf = Object.assign({}, this.defaultCurrencyConfig);

            conf.readOnly = true;
            conf.noEventListeners = true;

            return conf;
        },
        numericConfig: function() {
            var conf = Object.assign({}, this.defaultNumericConfig);

            conf.readOnly = true;
            conf.noEventListeners = true;

            return conf;
        }
    }
}); 