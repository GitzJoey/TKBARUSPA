var warehouseVue = new Vue ({
    el: '#warehouseVue',
    data: {
        warehouseList: [],
        statusDDL: [],
        unitDDL: [],
        mode: '',
        warehouse: { }
    },
    mounted: function () {
        this.mode = 'list';
        this.getAllWarehouse();
        this.getUnit();
        this.getLookupStatus();
    },
    methods: {
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then(isValid => {
                if (!isValid) { return; }
                this.errors.clear();
                Codebase.blocks('#warehouseCRUDBlock', 'state_toggle');
                if (this.mode == 'create') {
                    axios.post(route('api.post.warehouse.save').url(), new FormData($('#warehouseForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#warehouseCRUDBlock', 'state_toggle');
                    }).catch(e => {
                        this.handleErrors(e);
                        Codebase.blocks('#warehouseCRUDBlock', 'state_toggle');
                    });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.warehouse.edit', this.warehouse.hId).url(), new FormData($('#warehouseForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#warehouseCRUDBlock', 'state_toggle');
                    }).catch(e => {
                        this.handleErrors(e);
                        Codebase.blocks('#warehouseCRUDBlock', 'state_toggle');
                    });
                } else { }
            });
        },
        getAllWarehouse: function() {
            Codebase.blocks('#warehouseListBlock', 'state_toggle');
            axios.get(route('api.get.warehouse.read').url()).then(response => {
                this.warehouseList = response.data;
                Codebase.blocks('#warehouseListBlock', 'state_toggle');
            }).catch(e => {
                this.handleErrors(e);
                Codebase.blocks('#warehouseListBlock', 'state_toggle');
            });
        },
        createNew: function() {
            this.mode = 'create';
            this.errors.clear();
            this.warehouse = this.emptyWarehouse();
        },
        editSelected: function(idx) {
            this.mode = 'edit';
            this.errors.clear();
            this.warehouse = this.warehouseList[idx];
        },
        showSelected: function(idx) {
            this.mode = 'show';
            this.errors.clear();
            this.warehouse = this.warehouseList[idx];
        },
        deleteSelected: function(idx) {
            axios.post(route('api.post.warehouse.delete', idx).url()).then(response => {
                this.backToList();
            }).catch(e => { this.handleErrors(e); });
        },
        backToList: function() {
            this.mode = 'list';
            this.errors.clear();
            this.getAllWarehouse();
        },
        emptyWarehouse: function() {
            return {
                hId: '',
                name: '',
                address: '',
                phone_num: '',
                status: '',
                remarks: '',
                sections: [{
                    name: '',
                    position: '',
                    capacity: 0,
                    capacityUnitHId: '',
                    remarks: ''
                }]
            }
        },
        addSections: function() {
            this.warehouse.sections.push({
                name: '',
                position: '',
                capacity: 0,
                capacityUnitHId: '',
                remarks: ''
            });
        },
        removeSections: function(idx) {
            this.warehouse.sections.splice(idx, 1);
        },
        getLookupStatus: function() {
            axios.get(route('api.get.lookup.bycategory', 'STATUS').url()).then(
                response => { this.statusDDL = response.data; }
            );
        },
        getUnit: function() {
            axios.get(route('api.get.settings.unit.read').url()).then(
                response => { this.unitDDL = response.data; }
            );
        }
    },
    watch: {
        mode: function() {
            switch (this.mode) {
                case 'create':
                case 'edit':
                case 'show':
                    Codebase.blocks('#warehouseListBlock', 'close')
                    Codebase.blocks('#warehouseCRUDBlock', 'open')
                    break;
                case 'list':
                default:
                    Codebase.blocks('#warehouseListBlock', 'open')
                    Codebase.blocks('#warehouseCRUDBlock', 'close')
                    break;
            }
        }
    },
    computed: {
        defaultPleaseSelect: function() {
            return '';
        }
    }
});
