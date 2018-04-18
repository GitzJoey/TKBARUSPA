var roleVue = new Vue ({
    el: '#roleVue',
    data: {
        roleList: [],
        permissionDDL: [],
        mode: '',
        role: { }
    },
    mounted: function () {
        this.mode = 'list';
        this.getAllRole();
        this.getLookupPermission();
    },
    methods: {
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then(isValid => {
                if (!isValid) return;
                Codebase.blocks('#roleCRUDBlock', 'state_toggle');
                if (this.mode == 'create') {
                    axios.post(route('api.post.role.save').url(), new FormData($('#roleForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#roleCRUDBlock', 'state_toggle');
                    }).catch(e => { this.handleErrors(e); });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.role.edit', this.role.hId).url(), new FormData($('#roleForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#roleCRUDBlock', 'state_toggle');
                    }).catch(e => { this.handleErrors(e); });
                } else { }
            });
        },
        getAllRole: function() {
            Codebase.blocks('#roleListBlock', 'state_toggle');
            axios.get(route('api.get.role.read').url()).then(response => {
                this.roleList = response.data;
                Codebase.blocks('#roleListBlock', 'state_toggle');
            }).catch(e => { this.handleErrors(e); });
        },
        createNew: function() {
            this.mode = 'create';
            this.errors.clear();
            this.role = this.emptyRole();
        },
        editSelected: function(idx) {
            this.mode = 'edit';
            this.errors.clear();
            this.role = this.roleList[idx];
        },
    },
    function: {

    }
});