<template>
    <div>
        <el-form label-position="top" ref="form" :model="form">
            <el-row :gutter="24">
                <el-col :span="6">
                    <el-form-item label="Seleccione el dia:">
                        <el-select  @change="getPuntuacionMensual(form.mes)" v-model="form.mes" class="fullWidth">
                            <el-option
                                v-for="item in meses"
                                :key="item.valor"
                                :label="item.label"
                                :value="item.valor"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row v-show="showPuntos" :gutter="24">
                <el-col :span="6">
                    <el-form-item class="block card-puntuacion">
                        <span class="demonstration">¿Cómo calificarías la calidad de la comida durante este mes?</span>
                        <el-rate v-model="pregunta1" disabled style="width: 100%;"></el-rate>
                    </el-form-item>
                </el-col>
                <el-col :span="6">
                    <el-form-item class="block card-puntuacion">
                        <span class="demonstration">¿Estás satisfecho con el tamaño de las porciones?</span>
                        <el-rate v-model="pregunta2" disabled style="width: 100%;"></el-rate>
                    </el-form-item>
                </el-col>
                <el-col :span="6">
                    <el-form-item class="block card-puntuacion">
                        <span class="demonstration">¿Como calificarías la variedad del menú ofrecido?</span>
                        <el-rate v-model="pregunta3" disabled style="width: 100%;"></el-rate>
                    </el-form-item>
                </el-col>
                <el-col :span="6">
                    <el-form-item class="block card-puntuacion">
                        <span class="demonstration">¿Cómo calificarías la higiene y el mantenimiento del área del comedor?</span>
                        <el-rate v-model="pregunta4" disabled style="width: 100%;"></el-rate>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
        <el-row v-show="showPuntos" :gutter="24">
            <el-table
                :data="allPuntuacion"
                style="width: 100%">
                <el-table-column
                    prop="cedula"
                    label="Cedula"
                    width="170">
                </el-table-column>
                <el-table-column
                    prop="nombre"
                    label="Nombre"
                    width="170">
                </el-table-column>
                <el-table-column
                    prop="fecha"
                    label="Fecha"
                    width="170">
                </el-table-column>
                <el-table-column
                    label="Pregunta 1"
                    width="180">
                    <template slot-scope="scope">
                        <el-rate v-model="scope.row.pregunta1" disabled style="width: 100%;"></el-rate>
                    </template>
                </el-table-column>
                <el-table-column
                    label="Pregunta 2"
                    width="180">
                    <template slot-scope="scope">
                        <el-rate v-model="scope.row.pregunta2" disabled style="width: 100%;"></el-rate>
                    </template>
                </el-table-column>
                <el-table-column
                    label="Pregunta 3"
                    width="180">
                    <template slot-scope="scope">
                        <el-rate v-model="scope.row.pregunta3" disabled style="width: 100%;"></el-rate>
                    </template>
                </el-table-column>
                <el-table-column
                    label="Pregunta 4"
                    width="180">
                    <template slot-scope="scope">
                        <el-rate v-model="scope.row.pregunta4" disabled style="width: 100%;"></el-rate>
                    </template>
                </el-table-column>
                
            </el-table>
        </el-row>



    </div>
</template>

<script>
import { Loading } from 'element-ui';
export default {
    data() {
        return {
            pregunta1:0,
            pregunta2:0,
            pregunta3:0,
            pregunta4:0,
            allPuntuacion:[],
            showPuntos:false,
            form:{
                mes:'',
            },
            meses:[
                { valor: 1, label: 'Enero' },
                { valor: 2, label: 'Febrero' },
                { valor: 3, label: 'Marzo' },
                { valor: 4, label: 'Abril' },
                { valor: 5, label: 'Mayo' },
                { valor: 6, label: 'Junio' },
                { valor: 7, label: 'Julio' },
                { valor: 8, label: 'Agosto' },
                { valor: 9, label: 'Septiembre' },
                { valor: 10, label: 'Octubre' },
                { valor: 11, label: 'Noviembre' },
                { valor: 12, label: 'Diciembre' }
            ]
        };
    },
    created(){
        
    },
    mounted(){
        
    },
    methods: {

        getPuntuacionMensual(mes){
            let loadingInstance = Loading.service();
            this.showPuntos=false;
            axios.get('/rrhh/getPuntuacionMensual/'+mes)
            .then(response =>{
                if(response.data.message=="No se encontro ningun dato expecifico de esta mes."){
                    this.$message({
                        showClose: true,
                        message: 'No se encontro ningun dato expecifico de esta mes.',
                        type: 'error'
                    });
                    loadingInstance.close();
                }else if(response.data.message=="Exito!"){
                    this.pregunta1=response.data.pregunta1;
                    this.pregunta2=response.data.pregunta2;
                    this.pregunta3=response.data.pregunta3;
                    this.pregunta4=response.data.pregunta4;
                    this.allPuntuacion=response.data.allPuntuacion;
                    this.showPuntos=true;
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
        }
        
    },
};
</script>
<style>

</style>