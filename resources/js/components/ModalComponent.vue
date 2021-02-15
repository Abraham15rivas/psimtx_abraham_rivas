<template>
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header vino">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="close()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body body-card">
                <form action="">
                    <div class="orm-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">
                            Nombre:
                        </label>
                        <div class="col-md-6">
                            <input type="text" v-model="interest.name" class="from-control" name="name" id="name" required>
                        </div>
                    </div>
                    <div class="orm-group row">
                        <label for="description" class="col-md-4 col-form-label text-md-right">
                            Descripción
                        </label>
                        <div class="col-md-6">
                            <input type="text" v-model="interest.description" class="from-control" name="description" id="description" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer vino">
                <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal" @click="close()">Cerrar</button>
                <button type="button" :class="[ edit ? 'btn btn-primary' : 'btn btn-success' ]" @click="edit ? updateData() : setData()" v-text="`${ edit ? 'Actualizar' : 'Registrar' }`"></button>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['interest_edit'],
    data() {
        return {
            interest: {
                name: null,
                description: null
            },
            edit: false
        }        
    },
    methods: {
        close() {
            this.clear()
            this.edit = false
            let close = document.getElementById('close')
            close.click()
        },
        clear() {
            this.$emit('clear')
            this.interest.name = ''
            this.interest.description = ''
        },
        setData() {
            let params = {
                name: this.interest.name,
                description: this.interest.description
            }
            this.close()
            this.$emit('create', params)
        },
        updateData() {
            let params = {
                id: this.interest.id,
                name: this.interest.name,
                description: this.interest.description
            }
            this.close()
            this.$emit('update', params)
        }
    },
    watch: {
        interest_edit() {
            if(this.interest_edit != null) {
                this.edit = true
                this.interest = JSON.parse(JSON.stringify(this.interest_edit))
            }
        }
    }
}
</script>