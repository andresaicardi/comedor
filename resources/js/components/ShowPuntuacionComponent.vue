<template>
    <div>
        <el-form label-position="top" ref="form" :model="form">
            <el-row :gutter="24">
                <el-col :span="6">
                    <el-form-item label="Seleccione el dia:">
                        <el-date-picker
                            @change="getPuntuacion(form.semana)"
                            v-model="form.semana"
                            format="yyyy-MM-dd"
                            value-format="yyyy-MM-dd"
                            placeholder="Selecciona una semana"
                            style="width: 100%;"
                        ></el-date-picker>
                    </el-form-item>
                </el-col>
                <el-col v-show="showPuntos" :span="6">
                    <el-form-item label="Puntuacion Total" class="block card-puntuacion">
                        <el-rate v-model="puntos" disabled style="width: 100%;"></el-rate>
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
                    width="180">
                </el-table-column>
                <el-table-column
                    prop="nombre"
                    label="Nombre"
                    width="180">
                </el-table-column>
                <el-table-column
                    prop="fecha"
                    label="Fecha"
                    width="180">
                </el-table-column>
                <el-table-column
                    label="Puntuacion"
                    width="180">
                    <template slot-scope="scope">
                        <el-rate v-model="scope.row.valoracion" disabled style="width: 100%;"></el-rate>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="comentario"
                    label="Comentario">
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
            puntos:0,
            allPuntuacion:[],
            showPuntos:false,
            form:{
                semana:'',
            }
        };
    },
    created(){
        
    },
    mounted(){
        
    },
    methods: {
        getPuntuacion(semana){
            let loadingInstance = Loading.service();
            this.showPuntos=false;
            axios.get('/rrhh/getPuntuacion/'+semana)
            .then(response =>{
                if(response.data.message=="No se encontro ningun dato expecifico de esta semana."){
                    this.$message({
                        showClose: true,
                        message: 'No se encontro ningun dato expecifico de esta semana.',
                        type: 'error'
                    });
                    loadingInstance.close();
                }else if(response.data.message=="Exito!"){
                    this.puntos=response.data.puntos;
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