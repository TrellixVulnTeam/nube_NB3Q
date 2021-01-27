import { Injectable } from '@angular/core';

import { HttpClient } from '@angular/common/http';

import { Provincia } from './provincia';

import { Localidades } from './localidades';

@Injectable({
  providedIn: 'root'
})
export class PAjaxService {
  private url:string = "http://localhost/cliente/provinciaslocalidades/serviciosWeb/provinciaslocalidades/servidor.php";

  constructor(private http: HttpClient) { }

    pedirProvincias(){
      let dir = this.url + "?servicio=provincias"
      return this.http.get<Provincia[]>(dir);
    }

    pedirLocalidades(opcion){
      let dir = this.url + "?servicio=localidades&codigop=" + opcion;
      return this.http.get<Localidades[]>(dir);
    }
  
}
