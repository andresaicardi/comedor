<template>
    <div>
        <el-form ref="form" :model="form">
            <el-row :gutter="24">
                <el-col :span="12">
                    <el-form-item label="Mes">
                        <el-select v-model="form.mes" placeholder="Seleccione un mes" style="width: 100%;">
                            <el-option
                            v-for="(mes, index) in meses"
                            :key="index"
                            :label="mes"
                            :value="index + 1"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="24">
                    <el-button type="success" @click="getDatosMesColaboradores(form.mes)">Buscar Datos</el-button>
                </el-col>
            </el-row>
        </el-form>
        <br>
        <el-row v-show="showTabla" :gutter="24" >
            <el-col :span="18" :offset="3">
                <el-table
                    max-height="300"
                    border
                    stripe
                    :data="allDatos"
                    style="width: 100%;">
                    <el-table-column
                        prop="cedula"
                        label="Cedula">
                    </el-table-column>
                    <el-table-column
                        prop="nombre"
                        label="Nombre">
                    </el-table-column>
                    <el-table-column
                        prop="cantidad"
                        label="Cantidad">
                    </el-table-column>
                </el-table>
            </el-col>
        </el-row>
    </div>
  </template>
  
  <script>
  import { Loading } from 'element-ui';
  export default {
    data() {
      return {
        allDatos:[],
        showTabla:false,
        form:{
            mes: '',
        },
        meses: [
          "Enero",
          "Febrero",
          "Marzo",
          "Abril",
          "Mayo",
          "Junio",
          "Julio",
          "Agosto",
          "Septiembre",
          "Octubre",
          "Noviembre",
          "Diciembre"
        ]
      };
    },
    created(){
        
    },
    mounted(){
        
    },
    methods: {
        getDatosMesColaboradores(mes){
            let loadingInstance = Loading.service();
            axios.get('/rrhh/getDatosMesColaboradores/'+mes)
            .then(response =>{
                if(response.data.message=="No se encontro ningun dato expecifico de este mes."){
                    this.$message({
                        showClose: true,
                        message: "No se encontro ningun dato expecifico de este mes.",
                        type: 'error'
                    });
                    loadingInstance.close();
                }else if(response.data.message=="Exito!"){
                    this.allDatos=response.data.allDatos;
                    this.showTabla=true;
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
  