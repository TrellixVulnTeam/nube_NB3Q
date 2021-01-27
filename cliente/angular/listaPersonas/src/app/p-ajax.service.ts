import { Persona } from './persona';
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class PAjaxService {
  private url:string = "http://localhost/cliente/provinciaslocalidades/serviciosWeb/listaPersonas/servidor.php";

  constructor(private http: HttpClient) {

   }

   listar(){
    return this.http.post<Persona[]>(this.url, {servicio:"listar"});
   }

  }
