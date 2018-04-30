var userVue = new Vue ({
    el: '#userVue',
    data: {
        mode: '',
        providerDDL: [],
        companyDDL: [],
        rolesDDL: [],
        statusDDL: [],
        yesnoDDL: [],
        user: {
            profile: [{
                phone_numbers: [{}]
            }]
        },
        userList: []
    },
    mounted: function () {
        this.mode = 'list';
        this.getLookupStatus();
        this.getCompany();
        this.getRoles();
        this.getAllUser();
        this.getPhoneProvider();
        this.getLookupYesNo();
    },
    methods: {
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then(isValid => {
                if (!isValid) return;
                this.errors.clear();
                Codebase.blocks('#userCRUDBlock', 'state_toggle');
                if (this.mode == 'create') {
                    axios.post(route('api.post.settings.user.save').url(), new FormData($('#userForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#userCRUDBlock', 'state_toggle');
                    }).catch(e => {
                        this.handleErrors(e);
                        Codebase.blocks('#userCRUDBlock', 'state_toggle');
                    });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.settings.user.edit', this.user.hId).url(),
                        new FormData($('#userForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#userCRUDBlock', 'state_toggle');
                    }).catch(e => {
                        this.handleErrors(e);
                        Codebase.blocks('#userCRUDBlock', 'state_toggle');
                    });
                } else { }
            });
        },
        getAllUser: function(page) {
            Codebase.blocks('#userListBlock', 'state_toggle');
            axios.get(route('api.get.settings.user.read').url()).then(response => {
                this.userList = response.data;
                Codebase.blocks('#userListBlock', 'state_toggle');
            }).catch(e => {
                this.handleErrors(e);
                Codebase.blocks('#userListBlock', 'state_toggle');
            });
        },
        createNew: function() {
            this.mode = 'create';
            this.errors.clear();
            this.user = this.emptyUser();
        },
        editSelected: function(idx) {
            this.mode = 'edit';
            this.errors.clear();
            this.user = _.merge(this.emptyUser(), this.userList[idx]);
        },
        showSelected: function(idx) {
            this.mode = 'show';
            this.errors.clear();
            this.user = _.merge(this.emptyUser(), this.userList[idx]);
        },
        deleteSelected: function(idx) {
            axios.post(route('api.post.settings.user.delete', idx).url()).then(response => {
                this.backToList();
            }).catch(e => { this.handleErrors(e); });
        },
        backToList: function() {
            this.mode = 'list';
            this.errors.clear();
            this.getAllUser();
        },
        emptyUser: function() {
            return {
                hId: '',
                name: '',
                email: '',
                company: {},
                companyHId: '',
                roleHId: '',
                roles: [],
                active: -1,
                activeLookup: '',
                profile: [{
                    first_name: '',
                    last_name: '',
                    address: '',
                    ic_num: '',
                    phone_numbers: [{
                        hId: '',
                        phoneProviderHId: '',
                        number: '',
                        remarks: ''
                    }]
                }]
            }
        },
        addNewPhone: function() {
            this.user.profile[0].phone_numbers.push({
                hId: '',
                phoneProviderHId: '',
                number: '',
                remarks: ''
            });
        },
        removeSelectedPhone: function(idx) {
            this.user.profile[0].phone_numbers.splice(idx, 1);
        },
        getLookupStatus: function() {
            axios.get(route('api.get.lookup.bycategory', 'STATUS').url()).then(
                response => { this.statusDDL = response.data; }
            );
        },
        getCompany: function() {
            axios.get(route('api.get.settings.company.read').url()).then(
                response => { this.companyDDL = response.data; }
            );
        },
        getRoles: function() {
            axios.get(route('api.get.settings.role.read').url()).then(
                response => { this.rolesDDL = response.data; }
            );
        },
        getPhoneProvider: function() {
            axios.get(route('api.get.settings.phone_provider.read').url()).then(
                response => { this.providerDDL = response.data; }
            );
        },
        getLookupYesNo: function() {
            axios.get(route('api.get.lookup.bycategory', 'YESNOSELECT').url()).then(
                response => { this.yesnoDDL = response.data; }
            );
        }
    },
    watch: {
        "user.active": function() {
            if (this.user.active == 1) {
                this.user.activeLookup = 'YESNOSELECT.YES'
            } else if (this.user.active == 0) {
                this.user.activeLookup = 'YESNOSELECT.NO';
            } else {
                this.user.activeLookup = '';
            }
        },
        "user.activeLookup": function() {
            if (this.user.activeLookup == 'YESNOSELECT.YES') {
                this.user.active = 1;
            } else if (this.user.activeLookup == 'YESNOSELECT.NO') {
                this.user.active = 0;
            } else {
                this.user.active = -1;
            }
        },
        mode: function() {
            switch (this.mode) {
                case 'create':
                case 'edit':
                case 'show':
                    Codebase.blocks('#userListBlock', 'close')
                    Codebase.blocks('#userCRUDBlock', 'open')
                    break;
                case 'list':
                default:
                    Codebase.blocks('#userListBlock', 'open')
                    Codebase.blocks('#userCRUDBlock', 'close')
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
