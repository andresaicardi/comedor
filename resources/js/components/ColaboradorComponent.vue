<template>
    <div >
        <div v-if="scannerActive">
            <h1 class="qr-class">Escanea el QR para realizar el pedido</h1><br>
            <div class="centered-container">
                <div class="contenedor-camara">
                <StreamBarcodeReader
                    :constraints="cameraConstraints"
                    @decode="login"
                    @loaded="onLoaded"
                ></StreamBarcodeReader>
                </div>
            </div>
        </div>
        
        <el-form ref="form" :model="form">
            <div v-for="(opcion, index) in allMenu" :key="opcion.id_menu_dia" class="contenedorMenu">
                <h2 style="text-align: center;">{{ opcion.dia_semana }}</h2>
                <div class="carcasaMenuDia">
                    <div>
                        <el-card class="box-card editPropio"
                            :key="opcion.id_menu1"
                            :class="{ 'seleccionado': isSelectedMenu(opcion.id_menu_dia, opcion.id_menu1)}"
                            @click.native="selectMenu(opcion.id_menu_dia, opcion.id_menu1)">
                            <div slot="header" class="clearfix" style="word-break:break-all; font-weight: 600; font-size: large;">
                                <span>Menú 1</span> 
                            </div>
                            <div class="text item editDescripcion" >
                                {{ opcion.menu1 }}
                                <i class="el-icon-circle-check" v-if="isCheckMenu(opcion.id_menu_dia, opcion.id_menu1)"></i>
                            </div>
                        </el-card>
                    </div>
                    <div>
                        <el-card class="box-card editPropio"
                            
                            :key="opcion.id_menu2" 
                            :class="{ 'seleccionado': isSelectedMenu(opcion.id_menu_dia, opcion.id_menu2)}"
                            @click.native="selectMenu(opcion.id_menu_dia, opcion.id_menu2)">
                            <div slot="header" class="clearfix" style="word-break:break-all; font-weight: 600; font-size: large;">
                                <span>Menú 2</span> 
                            </div>
                            <div class="text item editDescripcion" >
                                {{ opcion.menu2 }}
                                <i class="el-icon-circle-check" v-if="isCheckMenu(opcion.id_menu_dia, opcion.id_menu2)"></i>
                            </div>
                        </el-card>
                    </div>
                    <div>
                        <el-card class="box-card editPropio"
                            
                            :key="opcion.id_menu3"
                            :class="{ 'seleccionado': isSelectedMenu(opcion.id_menu_dia, opcion.id_menu3)}"
                            @click.native="selectMenu(opcion.id_menu_dia, opcion.id_menu3)">
                            <div slot="header" class="clearfix" style="word-break:break-all; font-weight: 600; font-size: large;">
                                <span>Menú 3</span> 
                            </div>
                            <div class="text item editDescripcion" >
                                {{ opcion.menu3 }}
                                <i class="el-icon-circle-check" v-if="isCheckMenu(opcion.id_menu_dia, opcion.id_menu3)"></i>
                            </div>
                        </el-card>
                    </div>
                    <div >
                        <el-card class="box-card editPropio"
                            
                            :key="opcion.id_postre1"
                            :class="{ 'seleccionado': isSelectedPostre(opcion.id_menu_dia, opcion.id_postre1)}"
                            @click.native="selectPostre(opcion.id_menu_dia, opcion.id_postre1)">
                            <div slot="header" class="clearfix" style="word-break:break-all; font-weight: 600; font-size: large;">
                                <span>Postre 1</span> 
                            </div>
                            <div class="text item editDescripcion" >
                                {{ opcion.postre1 }}
                                <i class="el-icon-circle-check" v-if="isCheckPostre(opcion.id_menu_dia, opcion.id_postre1)"></i>
                            </div>
                        </el-card>
                    </div>
                    <div>
                        <el-card class="box-card editPropio"
                             
                            :key="opcion.id_postre2"
                            :class="{ 'seleccionado': isSelectedPostre(opcion.id_menu_dia, opcion.id_postre2)}"
                            @click.native="selectPostre(opcion.id_menu_dia, opcion.id_postre2)">
                            <div slot="header" class="clearfix" style="word-break:break-all; font-weight: 600; font-size: large;">
                                <span>Postre 2</span> 
                            </div>
                            <div class="text item editDescripcion" >
                                {{ opcion.postre2 }}
                                <i class="el-icon-circle-check" v-if="isCheckPostre(opcion.id_menu_dia, opcion.id_postre2)"></i>
                            </div>
                        </el-card>
                    </div>
                    <div>
                        <el-card class="box-card editPropio"
                            
                            :key="opcion.id_postre3"
                            :class="{ 'seleccionado': isSelectedPostre(opcion.id_menu_dia, opcion.id_postre3)}"
                            @click.native="selectPostre(opcion.id_menu_dia, opcion.id_postre3)">
                            <div slot="header" class="clearfix" style="word-break:break-all; font-weight: 600; font-size: large;">
                                <span>Postre 3</span> 
                            </div>
                            <div class="text item editDescripcion" >
                                {{ opcion.postre3 }}
                                <i class="el-icon-circle-check" v-if="isCheckPostre(opcion.id_menu_dia, opcion.id_postre3)"></i>
                            </div>
                        </el-card>
                    </div>
                </div>
                <div class="grupo-check">
                    <el-checkbox @change="selectPan(opcion.id_menu_dia,$event)" v-model="wantPanByDay[opcion.id_menu_dia]" :disabled="selectedAusentismo[opcion.id_menu_dia]">Quiero Pan!</el-checkbox>
                    <el-checkbox @change="selectAusentismo(opcion.id_menu_dia,$event)">Ausente</el-checkbox>
                </div>
            </div >
            <div v-if="botonesMenu" class="alinearDerecha editBoton">
                <el-button type="success" @click="postEnvio()" >Confirmar Pedido</el-button>
                <el-button type="danger" @click="cancelarPedido()">Cancelar</el-button>
            </div>

        </el-form>
    </div>
    
</template>

<script>
import { StreamBarcodeReader } from "vue-barcode-reader";

export default {
    components: {
        StreamBarcodeReader,
    },
    data() {
        return {
            dialogVisible: true,
            ocultar: false,
            dialogPuntuacion: false,
            botonesMenu: false,
            scannerActive: true,
            allMenu: [],
            selectedAusentismo: {},
            wantPanByDay: {},
            cameraConstraints: { video: { facingMode: 'user' } },  // Forzamos el uso de la cámara frontal

            formLogin: {
                qr: '',
            },

            form: {
                legajo: '',
                password: '',
                selectedMenus: {}, 
                selectedPostres: {},
                selectedPan: {},
                selectedAusentismo: {}
            },
        };
    },
    mounted() {
        this.setCameraConstraints();
    },
    methods: {
        setCameraConstraints() {
            // Configuramos las restricciones para forzar el uso de la cámara frontal
            this.cameraConstraints = { 
                video: { 
                    facingMode: 'user'  // Intentamos usar la cámara frontal
                } 
            };
        },
        onLoaded() {
            console.log('Cámara lista para escanear códigos de barra.');
        },

        async login(value) {
            this.formLogin.qr = value;
            this.scannerActive = false;

            if (this.formLogin.qr === 'SOMIL') {
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                let form = {
                    csrf: csrf
                };
                const response = await axios.post('/login/logout', form);
                if (response.status === 200) {
                    window.location.href = '/';
                } else {
                    this.$message({
                        showClose: true,
                        message: 'Hubo un error al cerrar sesión',
                        type: 'error'
                    });
                }
            }

            let form = this.formLogin;
            axios.post('/colaborador/loginInvitado', form)
            .then(response => {
                if (response.data.message === 'Bienvenido al Menú semana!') {
                    this.$message({
                        showClose: true,
                        message: 'Bienvenido al Menú semana!',
                        type: 'success'
                    });
                    const login = response.data.login;
                    this.getMenu(login.legajo);
                    this.dialogVisible = false;
                    this.form.legajo = login.legajo;
                    this.form.password = login.password;
                    this.formLogin.qr = '';

                } else if (response.data.message === 'No se encontró el usuario, el legajo no existe o no se encuentra') {
                    this.$message({
                        showClose: true,
                        message: 'No se encontró el usuario, el legajo no existe o no se encuentra',
                        type: 'error'
                    });
                    window.location.reload();
                } else if (response.data.message === 'La contraseña no es correcta') {
                    this.$message({
                        showClose: true,
                        message: 'La contraseña no es correcta',
                        type: 'error'
                    });
                    window.location.reload();
                } else {
                    this.$message({
                        showClose: true,
                        message: 'Ocurrió un inconveniente. Contactar con Sistemas',
                        type: 'error'
                    });
                    window.location.reload();
                }
            }).catch(error => {
                this.$message({
                    showClose: true,
                    message: error,
                    type: 'error'
                });
                window.location.reload();
            });
        },

        getMenu(legajo) {
            axios.get('/colaborador/getMenu/' + legajo)
            .then(response => {
                if (response.data.message === 'Ya se realizó el pedido!') {
                    this.$message({
                        showClose: true,
                        message: 'Ya se realizó el pedido!',
                        type: 'warning'
                    });
                    this.$alert('Ya se realizó el pedido!', {
                        type: 'warning',
                    }).then(response => {
                        window.location.reload();
                    });
                } else if (response.data.message === 'El periodo de elegir el Menú está cerrado') {
                    this.$message({
                        showClose: true,
                        message: 'El periodo de elegir el Menú está cerrado',
                        type: 'error'
                    });
                    this.$alert('El periodo de elegir el Menú está cerrado', {
                        type: 'danger',
                    }).then(response => {
                        window.location.reload();
                    });
                } else if (response.data.length === 0) {
                    this.$message({
                        showClose: true,
                        message: 'Menú no disponible',
                        type: 'error'
                    });
                    this.$alert('Menú no disponible', {
                        type: 'danger',
                    }).then(response => {
                        window.location.reload();
                    });
                } else {
                    this.allMenu = response.data;
                    this.botonesMenu = true;
                }
            });
        },

        selectMenu(idMenuDia, idMenu) {
            if (!this.selectedAusentismo[idMenuDia]) {
                this.$set(this.form.selectedMenus, idMenuDia, idMenu);
            }
        },

        isSelectedMenu(idMenuDia, idMenu) {
            return this.form.selectedMenus[idMenuDia] === idMenu;
        },

        isCheckMenu(idMenuDia, idMenu) {
            return this.form.selectedMenus[idMenuDia] === idMenu;
        },

        selectPostre(idMenuDia, idPostre) {
            if (!this.selectedAusentismo[idMenuDia]) {
                this.$set(this.form.selectedPostres, idMenuDia, idPostre);
            }
        },
        
        isSelectedPostre(idMenuDia, idPostre){
            return this.form.selectedPostres[idMenuDia] === idPostre;
        },

        isCheckPostre(idMenuDia, idPostre){
            return this.form.selectedPostres[idMenuDia] === idPostre;
        },

        selectPan(idMenuDia, event) {
            this.$set(this.form.selectedPan, idMenuDia, event);
        },

        selectAusentismo(idMenuDia, event) {
            if(event===true){
                delete this.form.selectedPostres[idMenuDia];
                delete this.form.selectedMenus[idMenuDia];
                delete this.form.selectedPan[idMenuDia];
                this.$set(this.form.selectedAusentismo, idMenuDia, event);
                this.$set(this.selectedAusentismo, idMenuDia, event);
            }else{
                delete this.form.selectedAusentismo[idMenuDia];
                delete this.form.selectedAusentismo[idMenuDia];
                this.$set(this.selectedAusentismo, idMenuDia, null);
            }

            if (event) {
                this.$set(this.wantPanByDay, idMenuDia, false);
            }
            
        },

        postEnvio(){

            if(this.form.legajo==null || this.form.legajo==''){
                this.$message({
                    showClose: true,
                    message: 'Ocurrió un error, el legajo no fue ingresado',
                    type: 'error'
                });
                return false;
            }

            if(this.form.password==null || this.form.password==''){
                this.$message({
                    showClose: true,
                    message: 'Ocurrió un error, la password no fue ingresada',
                    type: 'error'
                });
                return false;
            }

            if(Object.keys(this.form.selectedMenus).length<1){
                this.$message({
                    showClose: true,
                    message: 'Debe seleccionar un Menú para cada día!',
                    type: 'error'
                });
                return false;
            }

            if(Object.keys(this.form.selectedPostres).length<1){
                this.$message({
                    showClose: true,
                    message: 'Debe seleccionar un Postre para cada día!',
                    type: 'error'
                });
                return false;
            }

            axios.post('/colaborador/postEnvio', this.form)
            .then(response => {
                if(response.data.message=='Se ha realizado el pedido con éxito!'){
                    this.$message({
                        showClose: true,
                        message: 'Se ha realizado el pedido con éxito!',
                        type: 'success'
                    });
                    window.location.reload();
                    
                }else{
                    this.$message({
                        showClose: true,
                        message: 'Ocurrió un inconveniente. Contactar con Sistemas',
                        type: 'error'
                    });
                }
            }).catch(error => {
                this.$message({
                    showClose: true,
                    message: error,
                    type: 'error'
                });
            });
        },

        cancelarPedido(){
            window.location.reload();
        },

    },
}
</script>

<style>
    
    
</style>