import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Persona } from "./persona";

@Injectable({
  providedIn: 'root'
})
export class PAjaxService {

  private url:string= "http://localhost/cliente/provinciaslocalidades/serviciosWeb/listaPersonas/servidor.php";

  constructor(private http: HttpClient) { }

  //peti básica para cualquier instruccion
  peti(instruccion: Object){
    return this.http.post(this.url, JSON.stringify(instruccion));
  }

  //petilistar es una petición con la instruccion listar
  petilistar(){
    let instruccion = JSON.stringify({
      servicio: "listar"
    });
    return this.http.post<Persona[]>(this.url,instruccion);
  }

  petiInsertar(objeto:any){
    let objetoPet=JSON.stringify({
      servicio: "insertar",
      dni: objeto['dni'],
      nombre: objeto['nombre'],
      apellidos:objeto['apellidos'],
    });
    return this.http.post<Persona>(this.url,objetoPet);
  }

  petiBorrar(iden:any){
    let instruccion = JSON.stringify({
      servicio: "borrar",
      id:iden
    });
    return this.http.post(this.url,instruccion);
  }

  petiCargaPer(ide:number){
    let instruccion = JSON.stringify({
      servicio: "selPersonaID",
      id:ide
    });
    return this.http.post<Persona>(this.url,instruccion);
  }

  petiMod(objeto:any){
    let objetoPet=JSON.stringify({
      servicio: "modificar",
      dni: objeto['dni'],
      nombre: objeto['nombre'],
      apellidos: objeto['apellidos'],
      id: objeto['id']
    });
    return this.http.post(this.url,objetoPet);
  }

}
