var companyVue = new Vue ({
    el: '#companyVue',
    data: {
        companyList: [],
        statusDDL: [],
        yesnoDDL: [],
        bankDDL: [],
        mode: '',
        company: { }
    },
    mounted: function () {
        this.mode = 'list';
        this.getAllCompany();
        this.getLookupStatus();
        this.getLookupYesNo();
        this.getBank();
    },
    methods: {
        validateBeforeSubmit: function() {
            this.$validator.validateScopes().then(isValid => {
                if (!isValid) return;
                Codebase.blocks('#companyCRUDBlock', 'state_toggle');
                if (this.mode == 'create') {
                    axios.post(route('api.post.settings.company.save').url(), new FormData($('#companyForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#companyCRUDBlock', 'state_toggle');
                    }).catch(e => {
                        this.handleErrors(e);
                        Codebase.blocks('#companyCRUDBlock', 'state_toggle');
                    });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.settings.company.edit', this.company.hId).url(), new FormData($('#companyForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#companyCRUDBlock', 'state_toggle');
                    }).catch(e => {
                        this.handleErrors(e);
                        Codebase.blocks('#companyCRUDBlock', 'state_toggle');
                    });
                } else { }
            });
        },
        getAllCompany: function() {
            Codebase.blocks('#companyListBlock', 'state_toggle');
            axios.get(route('api.get.settings.company.read').url()).then(response => {
                this.companyList = response.data;
                Codebase.blocks('#companyListBlock', 'state_toggle');
            }).catch(e => { this.handleErrors(e); });
        },
        createNew: function() {
            this.mode = 'create';
            this.errors.clear();
            this.company = this.emptyCompany();
        },
        editSelected: function(idx) {
            this.mode = 'edit';
            this.errors.clear();
            this.company = this.companyList[idx];
        },
        showSelected: function(idx) {
            this.mode = 'show';
            this.errors.clear();
            this.company = this.companyList[idx];
        },
        deleteSelected: function(idx) {
            axios.post(route('api.post.settings.company.delete', idx).url()).then(response => {
                this.backToList();
            }).catch(e => { this.handleErrors(e); });
        },
        backToList: function() {
            this.mode = 'list';
            this.errors.clear();
            this.getAllCompany();
        },
        emptyCompany: function() {
            return {
                hId: '',
                name: '',
                status: '',
                is_default: '',
                frontweb: '',
                remarks: '',
                bank_accounts: [],
                date_format: 'd M Y',
                time_format: 'G:H:s',
                thousand_separator: ',',
                decimal_separator: '.',
                decimal_digit: '2',
                ribbon: 'default'
            }
        },
        addBankAccounts: function() {
            this.company.bank_accounts.push({
                'bankHId': '',
                'account_name': '',
                'account_number': '',
                'remarks': ''
            });
        },
        removeSelectedBankAccounts: function(idx) {
            this.company.bank_accounts.splice(idx, 1);
        },
        getLookupStatus: function() {
            axios.get(route('api.get.lookup.bycategory', 'STATUS').url()).then(
                response => { this.statusDDL = response.data; }
            );
        },
        getLookupYesNo: function() {
            axios.get(route('api.get.lookup.bycategory', 'YESNOSELECT').url()).then(
                response => { this.yesnoDDL = response.data; }
            );
        },
        getBank: function() {
            axios.get(route('api.get.bank.read').url()).then(
                response => { this.bankDDL = response.data; }
            );
        },
        displayDateTimeNow: function(format) {
            return moment().format(format);
        }
    },
    watch: {
        mode: function() {
            switch (this.mode) {
                case 'create':
                case 'edit':
                case 'show':
                    Codebase.blocks('#companyListBlock', 'close')
                    Codebase.blocks('#companyCRUDBlock', 'open')
                    break;
                case 'list':
                default:
                    Codebase.blocks('#companyListBlock', 'open')
                    Codebase.blocks('#companyCRUDBlock', 'close')
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
