<template>
    <div>
        <el-row :gutter="24">
            <el-form ref="form" :model="form">
                <el-col :span="12">
                    <el-form-item prop="dia" label="Dia de la semana">
                        <el-date-picker
                            v-model="form.dia"
                            type="date"
                            value-format='yyyy-MM-dd'
                            placeholder="Elegir el dia"
                            :picker-options="pickerOptions"
                            style="width: 100%;"
                        >
                        </el-date-picker>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="Hora">
                        <el-select style="width: 100%;" v-model="form.hora" placeholder="Selecciona un horario">
                                <el-option
                                    v-for="hora in horarios"
                                    :key="hora.value"
                                    :label="hora.label"
                                    :value="hora.value"
                                ></el-option>
                            </el-select>
                    </el-form-item>
                </el-col>
            </el-form>
        </el-row>
        <el-row :gutter="24">
            <el-col :span="6">
                <el-button type="success" @click="getPedidosDia()">Buscar</el-button>
            </el-col>
        </el-row>
        <el-divider></el-divider>
        <el-row v-show="isTable" :gutter="24" >
            <el-table
                max-height="300"
                :data="allDatos"
                style="width: 100%">
                <el-table-column
                    prop="hora"
                    label="Hora"
                    width="180">
                </el-table-column>
                <el-table-column
                    prop="cantidad"
                    label="Cantidad"
                    width="180">
                </el-table-column>
                <el-table-column
                    label="Pan"
                    width="180">
                    <template slot-scope="scope">
                        <span v-if="scope.row.pan===1">Si</span>
                        <span v-else>No</span>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="postre"
                    label="Postre"
                    width="180">
                </el-table-column>
                <el-table-column
                    prop="menu"
                    label="Menu">
                </el-table-column>
            </el-table>
        </el-row>

        <!-- <el-row v-for="(opcion, index) in allDatos" :key="index" :gutter="24">
            <el-col :span="24">
                <el-card class="box-card editPedido">
                    <div class="text item">
                        <span class="titulo-descripcion">Menu:</span> {{ opcion.menu }}
                    </div>
                    <div class="text item">
                        <span class="titulo-descripcion">Postre:</span> {{ opcion.postre }}
                    </div>
                    <div class="text item">
                        <span class="titulo-descripcion">Hora:</span> {{ opcion.hora }}
                    </div>
                    <div class="text item">
                        <span class="titulo-descripcion">Cantidad:</span> {{ opcion.cantidad }}
                    </div>
                </el-card>
            </el-col>
        </el-row> -->
        
    </div>
</template>

<script>
import { Loading } from 'element-ui';
export default {
    data() {
        return {
            allDatos:[],
            isTable:false,
            form:{
                dia:'',
                hora:''
            },

            horarios: [             
                { value: '11:10', label: '11:10' },
                { value: '11:50', label: '11:50' },
                { value: '12:10', label: '12:10' },
                { value: '12:30', label: '12:30' },
                { value: '13:00', label: '13:00' },
            ],

            pickerOptions: {
                disabledDate(time) {
                    // Deshabilitar fines de semana
                    const day = time.getDay();
                    if (day === 0 || day === 6) {
                        return true;
                    }

                    // Obtener la fecha del primer día de la semana actual (lunes)
                    const today = new Date();
                    const firstDayOfWeek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - today.getDay() + (today.getDay() === 1 ? 0 : (today.getDay() === 0 ? -6 : 1)));

                    // Deshabilitar fechas anteriores al primer día de la semana actual
                    return time < firstDayOfWeek;
                },
            },
        }
    },
    created(){
        
    },
    mounted(){
        
    },
    methods: {
        getPedidosDia(){
            let loadingInstance = Loading.service();
            let fecha=this.form.dia;
            let hora=this.form.hora;
            axios.get('/menu/getPedidosDia/'+fecha+'/'+hora)
            .then(response =>{
                if(response.data.message=="No se encontro ningun dato expecifico de esta semana"){
                    this.$message({
                        showClose: true,
                        message: "No se encontro ningun dato expecifico de esta semana",
                        type: 'error'
                    });
                    loadingInstance.close();
                }else if(response.data.message=="Exito!"){
                    this.allDatos=response.data.allDatos;
                    this.isTable=true;
                    loadingInstance.close();
                }else{
                    this.$message({
                        showClose: true,
                        message: 'Ocurrio un error, contactarse con el area de sistemas',
                        type: 'error'
                    });
                    loadingInstance.close();
                }
            });
        },
        
    },
};
</script>
<style>
    .editPedido .el-card__body{
        display: flex;
        justify-content: space-between;
    }

    .titulo-descripcion{
        font-weight: 600;
        font-size: 17px;
    }
</style>