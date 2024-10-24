<template>
    <div >
        <div v-if="scannerActive">
            <h1 class="qr-class">Escanea el QR para realizar el pedido</h1><br>
            <div class="centered-container">
                <div class="contenedor-camara">
                <StreamBarcodeReader
                    :constraints="cameraConstraints"  <!-- Aquí pasamos las constraints para la cámara frontal -->
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
                    <!-- Aquí va todo tu código de los menús, lo mantengo igual -->
                </div>
                <div class="grupo-check">
                    <el-checkbox @change="selectPan(opcion.id_menu_dia,$event)" v-model="wantPanByDay[opcion.id_menu_dia]" :disabled="selectedAusentismo[opcion.id_menu_dia]">Quiero Pan!</el-checkbox>
                    <el-checkbox @change="selectAusentismo(opcion.id_menu_dia,$event)">Ausente</el-checkbox>
                </div>
            </div>
            <div v-if="botonesMenu" class="alinearDerecha editBoton">
                <el-button type="success" @click="postEnvio()">Confirmar Pedido</el-button>
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
            cameraConstraints: { video: true },  // Constraints para la cámara (actualizaremos esto después)

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
        this.setFrontCamera();  // Intentamos establecer la cámara frontal al montar el componente
    },
    methods: {
        // Método para seleccionar la cámara frontal
        setFrontCamera() {
            // Enumeramos los dispositivos multimedia
            navigator.mediaDevices.enumerateDevices().then(devices => {
                const videoDevices = devices.filter(device => device.kind === 'videoinput');
                const frontCamera = videoDevices.find(device => device.label.toLowerCase().includes('front') || device.label.toLowerCase().includes('user'));

                if (frontCamera) {
                    // Si encontramos una cámara frontal, la seleccionamos
                    this.cameraConstraints = {
                        video: { deviceId: { exact: frontCamera.deviceId } }
                    };
                } else if (videoDevices.length > 0) {
                    // Si no encontramos una cámara frontal, usamos la primera disponible
                    this.cameraConstraints = {
                        video: { deviceId: { exact: videoDevices[0].deviceId } }
                    };
                }
            }).catch(error => {
                console.error('Error al enumerar dispositivos:', error);
                // Si hay un error, usamos cualquier cámara disponible
                this.cameraConstraints = { video: true };
            });
        },

        // Método cuando la cámara está lista
        onLoaded() {
            console.log('Cámara lista para escanear códigos de barra.');
        },

        // Método login al escanear
        async login(value) {
            this.formLogin.qr = value;
            this.scannerActive = false;

            if (this.formLogin.qr === 'SOMIL') {
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                let form = { csrf };
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
                    this.$alert('Ya se realizó el pedido!', { type: 'warning' }).then(() => {
                        window.location.reload();
                    });
                } else if (response.data.message === 'El periodo de elegir el Menú está cerrado') {
                    this.$message({
                        showClose: true,
                        message: 'El periodo de elegir el Menú está cerrado',
                        type: 'error'
                    });
                    this.$alert('El periodo de elegir el Menú está cerrado', { type: 'danger' }).then(() => {
                        window.location.reload();
                    });
                } else if (response.data.length === 0) {
                    this.$message({
                        showClose: true,
                        message: 'Menú no disponible',
                        type: 'error'
                    });
                    this.$alert('Menú no disponible', { type: 'danger' }).then(() => {
                        window.location.reload();
                    });
                } else {
                    this.allMenu = response.data;
                    this.botonesMenu = true;
                }
            });
        },

        // Métodos para la selección de menús y postres (todo esto lo mantengo igual)
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
        
        isSelectedPostre(idMenuDia, idPostre) {
            return this.form.selectedPostres[idMenuDia] === idPostre;
        },

        isCheckPostre(idMenuDia, idPostre) {
            return this.form.selectedPostres[idMenuDia] === idPostre;
        },

        selectPan(idMenuDia, event) {
            this.$set(this.form.selectedPan, idMenuDia, event);
        },

        selectAusentismo(idMenuDia, event) {
            if (event === true) {
                delete this.form.selectedPostres[idMenuDia];
                delete this.form.selectedMenus[idMenuDia];
                delete this.form.selectedPan[idMenuDia];
                this.$set(this.form.selectedAusentismo, idMenuDia, event);
                this.$set(this.selectedAusentismo, idMenuDia, event);
            } else {
                delete this.form.selectedAusentismo[idMenuDia];
                this.$set(this.selectedAusentismo, idMenuDia, null);
            }

            if (event) {
                this.$set(this.wantPanByDay, idMenuDia, false);
            }
        },

        postEnvio() {
            if (this.form.legajo == null || this.form.legajo === '') {
                this.$message({
                    showClose: true,
                    message: 'Ocurrió un error, el legajo no fue ingresado',
                    type: 'error'
                });
                return false;
            }

            if (this.form.password == null || this.form.password === '') {
                this.$message({
                    showClose: true,
                    message: 'Ocurrió un error, la password no fue ingresada',
                    type: 'error'
                });
                return false;
            }

            if (Object.keys(this.form.selectedMenus).length < 1) {
                this.$message({
                    showClose: true,
                    message: 'Debe seleccionar un Menú para cada día!',
                    type: 'error'
                });
                return false;
            }

            if (Object.keys(this.form.selectedPostres).length < 1) {
                this.$message({
                    showClose: true,
                    message: 'Debe seleccionar un Postre para cada día!',
                    type: 'error'
                });
                return false;
            }

            axios.post('/colaborador/postEnvio', this.form)
            .then(response => {
                if (response.data.message === 'Se ha realizado el pedido con éxito!') {
                    this.$message({
                        showClose: true,
                        message: 'Se ha realizado el pedido con éxito!',
                        type: 'success'
                    });
                    window.location.reload();
                } else {
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

        cancelarPedido() {
            window.location.reload();
        },
    }
};
</script>

<style>
/* Mantenemos los estilos como los tienes */
</style>