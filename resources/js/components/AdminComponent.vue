<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header vino">Datos</div>
                    <div class="card-body body-card">
                        <span v-if="user != null">
                            <p v-text="`Nombre: ${ user.name }`"></p>
                            <p v-text="`Correo: ${ user.email }`"></p>
                            <p v-text="`Nivel de acceso: ${ user.role.name != undefined ? user.role.name : '' }`"></p>
                            <p v-text="`Descripción: ${ user.role.description != undefined ? user.role.description : '' }`"></p>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header vino">Intereses</div>
                    <div class="card-body body-card">
                        <div class="text-right mb-3">
                            <button class="btn btn-success">Nuevo</button>
                        </div>
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Opcción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(interest, index) of interests" :key="index">
                                    <td v-text="interest.name"></td>
                                    <td v-text="interest.description"></td>
                                    <td>
                                        <button class="btn btn-warning">Editar</button>
                                        <button class="btn btn-danger">Eliminar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header vino">Usuarios</div>
                    <div class="card-body body-card">
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Opcción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(user_all, index) of users" :key="index">
                                    <td v-text="user_all.name"></td>
                                    <td v-text="user_all.email"></td>
                                    <td>
                                        <button class="btn btn-warning">Editar</button>
                                        <button class="btn btn-danger">Bloquear</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Swal from 'sweetalert2'
    export default {
        data() {
            return {
                count: 1,
                user: null,
                interests: [],
                users: []
            }
        },
        methods: {
            async getUSer() {
                try {
                    const url = `/user`
                    let response = await axios.get(url)
                    if(response) {
                        this.user = response.data
                    }
                } catch (error) {
                    console.log(error)
                }
            },
             async getAllUSer() {
                try {
                    const url = `/admin/users/all`
                    let response = await axios.get(url)
                    if(response) {
                        this.users = response.data
                    }
                } catch (error) {
                    console.log(error)
                }
            },
            async getInterests() {
                try {
                    const url = `/admin/interests`
                    let response = await axios.get(url)
                    if(response) {
                        this.interests = response.data
                    }
                } catch (error) {
                    console.log(error)
                }
            },
        },
        beforeMount() {
            this.getUSer()
            this.getAllUSer()
            this.getInterests()
        }
    }
</script>
