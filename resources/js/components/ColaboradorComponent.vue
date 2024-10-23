<template>
    <div >
        <div v-if="scannerActive">
            <h1 class="qr-class">Escanea el QR para realizar el pedido</h1><br>
            <div class="centered-container">
                <div class="contenedor-camara">
                <StreamBarcodeReader
                    :constraints="cameraConstraints"  <!-- Aquí añadimos las constraints -->
                    @decode="login"
                    @loaded="onLoaded"
                ></StreamBarcodeReader>
                </div>
            </div>
        </div>
        
        <!-- El resto de tu código permanece igual -->
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
            cameraConstraints: { video: true },  // Añadimos esta propiedad

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
        this.setFrontCamera();
    },
    methods: {
        setFrontCamera() {
            navigator.mediaDevices.enumerateDevices().then(devices => {
                const videoInputDevices = devices.filter(device => device.kind === 'videoinput');
                const frontCamera = videoInputDevices.find(device => device.label.toLowerCase().includes('front') || device.label.toLowerCase().includes('user'));

                if (frontCamera) {
                    this.cameraConstraints = {
                        video: {
                            deviceId: frontCamera.deviceId
                        }
                    };
                } else {
                    this.cameraConstraints = {
                        video: true // Fallback a la cámara predeterminada
                    };
                }
            }).catch(error => {
                console.error("Error al obtener dispositivos de video: ", error);
                this.cameraConstraints = { video: true };
            });
        },
        onLoaded() {
            console.log(`Ready to start scanning barcodes`);
        },

        async login(value) {
            this.formLogin.qr = value;
            this.scannerActive = false;

            if (this.formLogin.qr == 'SOMIL') {
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
                        message: 'Hubo un error a la hora de cerrar sesión',
                        type: 'error'
                    });
                }
            }

            let form = this.formLogin;
            axios.post('/colaborador/loginInvitado', form)
            .then(response => {
                if (response.data.message == 'Bienvenido al Menú semana!') {
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

                } else if (response.data.message == 'No se encontró el usuario, el legajo no existe o no se encuentra') {
                    this.$message({
                        showClose: true,
                        message: 'No se encontró el usuario, el legajo no existe o no se encuentra',
                        type: 'error'
                    });
                    window.location.reload();
                } else if (response.data.message == 'La contraseña no es correcta') {
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
                if (response.data.message == 'Ya se realizó el pedido!') {
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
                } else if (response.data.message == 'El periodo de elegir el Menú está cerrado') {
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
                } else if (response.data.length == 0) {
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

        // El resto de tus métodos permanece igual...
    }
};
</script>