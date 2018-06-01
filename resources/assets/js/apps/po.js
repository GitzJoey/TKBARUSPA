var poVue = new Vue ({
    el: '#poVue',
    data: {
        poList: [],
        supplierTypeDDL: [],
        poTypeDDL: [],
        supplierDDL: [],
        vendorTruckingDDL: [],
        expenseTypeDDL: [],
        warehouseDDL: [],
        selectedDate: new Date(),
        selectedSupplier: {},
        poStatusDesc: '',
        product_options: [],
        productSelected: '',
        isFinishLoadingMounted: false,
        allProduct: [],
        mode: '',
        po: {
            items:[],
            expenses: [],
            supplier: {
                hId: '',
                name: ''
            },
            discount: 0,
            subtotal: 0,
            grandtotal: 0
        },
        allPODates: []
    },
    mounted: function () {
        this.mode = 'list';
        this.renderPOListData();

        Promise.all([
            this.getSupplier(),
            this.getSupplierType(),
            this.getPOType(),
            this.getWarehouse(),
            this.getVendorTrucking(),
            this.getAllProduct(),
            this.getExpenseType()
        ]).then(() => {
            this.isFinishLoadingMounted = true;
        });
    },
    methods: {
        validateBeforeSubmit: function () {
            this.$validator.validateAll().then(isValid => {
                if (!isValid) return;
                this.errors.clear();
                this.loadingPanel('#poCRUDBlock', 'TOGGLE');
                if (this.mode == 'create') {
                    axios.post(route('api.post.po.save'), new FormData($('#poForm')[0])).then(response => {
                        this.backToList();
                        this.loadingPanel('#poCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#poCRUDBlock', 'TOGGLE');
                    });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.po.edit', this.po.hId), new FormData($('#poForm')[0])).then(response => {
                        this.backToList();
                        this.loadingPanel('#poCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#poCRUDBlock', 'TOGGLE');
                    });
                } else { }
            });
        },
        renderPOListData: function () {
            this.loadingPanel('#poListBlock', 'TOGGLE');

            var seldate = moment(this.selectedDate).formatPHP(this.databaseDateFormat);

            Promise.all([
                this.getAllPODates(),
                this.getAllPOData(seldate)
            ]).then(() => {
                this.loadingPanel('#poListBlock', 'TOGGLE');
            });
        },
        getAllPOData: function(date) {
            return new Promise((resolve, reject) => {
                var qS = [];
                qS.push({'key': 'date', 'value': date});

                axios.get(route('api.get.po.read').url() + this.generateQueryStrings(qS)).then(response => {
                    this.poList = response.data;
                    resolve(true);
                }).catch(e => {
                    this.handleErrors(e);
                    reject(e.response.data.message);
                });
            });
        },
        onChangeSupplierType: function (type) {
            if (type == 'SUPPLIERTYPE.WI') {
                this.po.supplierHId = '';
            }
        },
        createNew: function () {
            this.mode = 'create';
            this.errors.clear();
            this.po = this.emptyPO();
        },
        editSelected: function (idx) {
            this.mode = 'edit';
            this.errors.clear();
            this.po  = _.merge({
                discount: 0,
                subtotal: 0,
                grandtotal: 0,
                items: []
            }, this.poList[idx]);
        },
        showSelected: function (idx) {
            this.mode = 'show';
            this.errors.clear();
            this.po  = _.merge({
                discount: 0,
                subtotal: 0,
                grandtotal: 0,
                items: []
            }, this.poList[idx]);
        },
        deleteSelected: function (idx) {
            axios.post('/api/post/po/delete/' + idx).then(response => {
                this.backToList();
            }).catch(e => {
                this.handleErrors(e);
            });
        },
        backToList: function () {
            this.mode = 'list';
            this.errors.clear();
            this.renderPOListData();
        },
        emptyPO: function () {
            return {
                hId: '',
                code: this.generatePOCode(),
                po_created: new Date(),
                shipping_date: new Date(),
                supplier_type: '',
                supplierHId: '',
                warehouseHId: '',
                vendorTruckingHId: '',
                po_type: '',
                status: 'POSTATUS.D',
                productHId: '',
                items: [],
                expenses: [],
                supplier: {
                    hId: '',
                    name: ''
                },
                discount: 0,
                subtotal: 0,
                grandtotal: 0
            }
        },
        onChangeProductSelected(productId) {
            this.insertItem(productId);
        },
        insertItem: function (productId) {
            if(productId != ''){
                let prd = _.cloneDeep(_.find(this.product_options, { hId: productId }));
                this.po.items.push({
                    product: prd,
                    selected_product_unit: this.defaultProductUnit(),
                    base_product_unit: _.cloneDeep(_.find(prd.product_units, {is_base: 1})),
                    quantity: 0,
                    price: 0,
                    discount: 0,
                    total: 0
                });
            }
        },
        removeItem: function (index) {
            this.po.items.splice(index, 1);
        },
        addExpense: function () {
            this.po.expenses.push({
                hId: '',
                name: '',
                type: '',
                is_internal_expense: false,
                is_internal_expense_val: 0,
                amount: 0,
                remarks: ''
            });
        },
        removeExpense: function (index) {
            this.po.expenses.splice(index, 1);
        },
        defaultProductUnit: function(){
            return {
                hId: '',
                unit: {
                    hId: ''
                },
                conversion_value: 1
            };
        },
        onChangeProductUnit: function(itemIndex) {
            if (this.po.items[itemIndex].selected_product_unit.hId == '') {
                this.po.items[itemIndex].selected_product_unit = this.defaultProductUnit();
            } else {
                var pUnit = _.find(this.po.items[itemIndex].product.product_units, { hId: this.po.items[itemIndex].selected_product_unit.hId });
                _.merge(this.po.items[itemIndex].selected_product_unit, pUnit);
            }
        },
        getSupplier: function() {
            return new Promise((resolve, reject) => {
                axios.get(route('api.get.supplier.read').url() + this.generateQueryStrings([{'key':'all', 'value':'yes'}])).then(
                    response => {
                        this.supplierDDL = response.data;
                        resolve(true);
                    }
                ).catch(e => {
                    this.handleErrors(e);
                    reject(e.response.data.message);
                });
            });
        },
        getSupplierType: function() {
            return new Promise((resolve, reject) => {
                axios.get(route('api.get.lookup.bycategory', 'SUPPLIER_TYPE').url()).then(
                    response => {
                        this.supplierTypeDDL = response.data;
                        resolve(true);
                    }
                ).catch(e => {
                    this.handleErrors(e);
                    reject(e.response.data.message);
                });
            });
        },
        getPOType: function() {
            return new Promise((resolve, reject) => {
                axios.get(route('api.get.lookup.bycategory', 'PO_TYPE').url()).then(
                    response => {
                        this.poTypeDDL = response.data;
                        resolve(true);
                    }
                ).catch(e => {
                    this.handleErrors(e);
                    reject(e.response.data.message);
                });
            });
        },
        getWarehouse: function() {
            return new Promise((resolve, reject) => {
                axios.get(route('api.get.warehouse.read').url()).then(response => {
                    this.warehouseDDL = response.data;
                    resolve(true);
                }).catch(e => {
                    this.handleErrors(e);
                    reject(e.response.data.message);
                });
            });
        },
        getVendorTrucking: function() {
            return new Promise((resolve, reject) => {
                axios.get(route('api.get.truck.vendor_trucking.read').url()).then(response => {
                    this.vendorTruckingDDL = response.data;
                    resolve(true);
                }).catch(e => {
                    this.handleErrors(e);
                    reject(e.response.data.message);
                });
            });
        },
        getAllProduct: function() {
            return new Promise((resolve, reject) => {
                axios.get(route('api.get.product.readall').url()).then(response => {
                    this.allProduct = response.data;
                    resolve(true);
                }).catch(e => {
                    this.handleErrors(e);
                    reject(e.response.data.message);
                });
            });
        },
        generatePOCode: function() {
            axios.get(route('api.get.po.generate.po_code').url()).then(
                response => { this.po.code = response.data; }
            );
        },
        getExpenseType: function() {
            axios.get(route('api.get.lookup.bycategory', 'EXPENSE_TYPE').url()).then(
                response => { this.expenseTypeDDL = response.data; }
            );
        },
        calculateTotal: function() {
            var allItemTotal = 0;
            _.forEach(this.po.items, function(item, key) {
                var itemTotal = 0;
                itemTotal = (item.selected_product_unit.conversion_value * item.quantity * item.price) - item.discount;
                item.total = itemTotal;

                allItemTotal += itemTotal;
            });

            var expenseTotal = 0;
            _.forEach(this.po.expenses, function (expense, key) {
                if (expense.type.code === 'EXPENSETYPE.ADD')
                    expenseTotal += expense.amount;
                else
                    expenseTotal -= expense.amount;
            });

            this.po.subtotal = allItemTotal + expenseTotal;
            this.po.grandtotal = this.po.subtotal - this.po.discount;
        },
        getAllPODates: function() {
            return new Promise((resolve, reject) => {
                axios.get(route('api.get.po.by.dates').url()).then(response => {
                    this.allPODates = response.data;
                    resolve(true);
                }).catch(e => {
                    this.handleErrors(e);
                    reject(e.response.data.message);
                });
            });
        },
        populateSupplier: function(id) {
            this.po.supplier = _.cloneDeep(_.find(this.supplierDDL, { hId: id }));
        }
    },
    watch: {
        'po.supplierHId': function() {
            if (this.po.supplierHId == '') {
                this.selectedSupplier = {};
                this.product_options = [];
            } else {
                this.selectedSupplier = _.find(this.supplierDDL, { hId: this.po.supplierHId });
                this.product_options = this.selectedSupplier.products;
            }
        },
        'po.supplier_type': function() {
            if (this.po.supplier_type == 'SUPPLIERTYPE.WI') {
                this.product_options = this.allProduct;
            }
        },
        'po.status': function() {
            if (this.po.status != '') {
                axios.get(route('api.get.lookup.description.byvalue', 'POSTATUS.D').url()).then(
                    response => { this.poStatusDesc = response.data; }
                );
            }
        },
        'po.items': {
            deep: true,
            handler: function(oldVal, newVal) {
                this.calculateTotal();
            }
        },
        'po.expenses': {
            deep: true,
            handler: function(oldVal, newVal) {
                this.calculateTotal();
            }
        },
        mode: function() {
            switch (this.mode) {
                case 'create':
                case 'edit':
                case 'show':
                    this.contentPanel('#poListBlock', 'CLOSE')
                    this.contentPanel('#poCRUDBlock', 'OPEN')
                    break;
                case 'list':
                default:
                    this.contentPanel('#poListBlock', 'OPEN')
                    this.contentPanel('#poCRUDBlock', 'CLOSE')
                    break;
            }
        }
    },
    computed: {
        flatPickrInlineConfig: function() {
            var conf = Object.assign({}, this.defaultFlatPickrConfig);

            conf.inline = true;
            conf.altInputClass = 'hideTextBox';
            conf.enableTime = false;
            conf.enable = this.allPODates;

            return conf;
        },
        percentageFormatToString: function() {
            var conf = Object.assign({}, this.defaultPercentageConfig);

            conf.readOnly = true;
            conf.noEventListeners = true;

            return conf;
        },
        numericFormatToString: function() {
            var conf = Object.assign({}, this.defaultNumericConfig);

            conf.readOnly = true;
            conf.noEventListeners = true;

            return conf;
        },
        currencyFormatToString: function() {
            var conf = Object.assign({}, this.defaultCurrencyConfig);

            conf.readOnly = true;
            conf.noEventListeners = true;

            return conf;
        },
        defaultPleaseSelect: function() {
            return '';
        }
    }
});