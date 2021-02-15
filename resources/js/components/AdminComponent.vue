<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="mb-3 col-md-12 text-center">
                <h3>Panel Administrativo</h3>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header vino">Intereses</div>
                    <div class="card-body body-card">
                        <div class="text-right mb-3">
                            <button class="btn btn-success" data-toggle="modal" data-target="#exampleModalCenter">Nuevo</button>
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
                                        <button class="btn btn-warning" data-toggle="modal" data-target="#exampleModalCenter" @click="editInterest(index)">Editar</button>
                                        <button class="btn btn-danger" @click="delInterest(interest.id, index)">Eliminar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header vino">Usuarios</div>
                    <div class="card-body body-card">
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Cambio password</th>
                                    <th>Opcción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(user_all, index) of users" :key="index">
                                    <td v-text="user_all.email"></td>
                                    <td v-text="user_all.name"></td>
                                    <td>
                                        <label :for="`password${ index }`">Password</label>
                                        <input type="password" :name="`password${ index }`" :id="`password${ index }`">
                                    </td>
                                    <td>
                                        <button class="btn btn-warning" @click="changePassword(user_all.id, index)">Actualizar</button>
                                        <button :class="[user_all.status ? 'btn btn-danger' : 'btn btn-success']" @click="toBlock(user_all.id, index)" v-text="`${ user_all.status ? 'Bloquear' : 'Desbloquear'}`"></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <modal-component
                :interest_edit="interest"
                @create="setInterest"
                @update="upInterest"
                @clear="clearInterest"
            ></modal-component>
        </div>
    </div>
</template>

<script>
    import Swal from 'sweetalert2'
    import ModalComponent from './ModalComponent'
    export default {
        data() {
            return {
                count: 1,
                user: null,
                interests: [],
                users: [],
                interest: null
            }
        },
        components: {
            "modal-component": ModalComponent
        },
        methods: {
            clearInterest() {
                this.interest = null
            },
            editInterest(index) {
                this.interest = this.interests[index]
            },
            async setInterest(value) {
                try {
                    const url = `/admin/interests/store`
                    let params = {
                        name: value.name,
                        description: value.description
                    }
                    let response = await axios.post(url, params)
                    if(response.data) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Registrado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.getInterests()
                    }
                } catch (error) {
                    console.log(error)
                }
            },
            async upInterest(value) {
                try {
                    const url = `/admin/interests/update/${ value.id }`
                    let params = {
                        name: value.name,
                        description: value.description
                    }
                    let response = await axios.put(url, params)
                    if(response.data) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Actualizado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.getInterests()
                    }
                } catch (error) {
                    console.log(error)
                }
            },
            async delInterest(id, index) {
                try {
                    const url = `/admin/interests/delete/${ id }`
                    let response = await axios.delete(url)
                    if(response.data) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Borrado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.interests.splice(index, 1)
                    }
                } catch (error) {
                    console.log(error)
                }
            },
            async toBlock(id, index) {
                try {
                    let url = `/admin/status/${ id }`
                    const response = await axios.get(url)
                    if(response.data) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Cambiado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.users[index].status = !this.users[index].status
                    }
                } catch (error) {
                    console.log(error)
                }
            },
            async changePassword(id, index) {
                try {
                    let passwd = document.getElementById(`password${index}`).value
                    if (passwd.length > 5 && passwd.length < 17) {
                        let url = `/admin/password/${ id }`
                        let params = {
                            password: passwd
                        }
                        const response = await axios.post(url, params)
                        if (response.data == true) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Cambiado correctamente',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'El campo debe contener mínimo 6 caracteres y máximo 16',
                        })
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Solo se permiten estos caracteres: *.#?¿`,
                    })
                }
            },
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