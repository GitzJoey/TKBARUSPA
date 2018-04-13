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
                Codebase.blocks('#warehouseCRUDBlock', 'state_toggle');
                if (this.mode == 'create') {
                    axios.post('/api/post/warehouse/save', new FormData($('#warehouseForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#warehouseCRUDBlock', 'state_toggle');
                    }).catch(e => { this.handleErrors(e); });
                } else if (this.mode == 'edit') {
                    axios.post('/api/post/warehouse/edit/' + this.warehouse.hId, new FormData($('#warehouseForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#warehouseCRUDBlock', 'state_toggle');
                    }).catch(e => { this.handleErrors(e); });
                } else { }
            });
        },
        getAllWarehouse: function() {
            Codebase.blocks('#warehouseListBlock', 'state_toggle');
            axios.get('/api/get/warehouse/read').then(response => {
                this.warehouseList = response.data;
                Codebase.blocks('#warehouseListBlock', 'state_toggle');
            }).catch(e => { this.handleErrors(e); });
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
            axios.post('/api/post/warehouse/delete/' + idx).then(response => {
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
            axios.get('/api/get/lookup/byCategory/STATUS').then(
                response => { this.statusDDL = response.data; }
            );
        },
        getUnit: function() {
            axios.get('/api/get/unit/read').then(
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
