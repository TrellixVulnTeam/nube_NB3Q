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

   anade(p:Persona){
    let nuevo= JSON.parse(JSON.stringify(p));
    nuevo.servicio="insertar";
    console.log("nuevo(en el servicio): ", nuevo);

    return this.http.post<Array<Persona>>(this.url, nuevo);
   }

   selPersonaId(id: number){
    let p= {
      servicio: "selPersonaID",
      id: id
    }
    console.log("p= ", p);
    return this.http.post<Persona>(this.url, p);
   }

   modificar(p:Persona){
    let nuevo= JSON.parse(JSON.stringify(p));
    nuevo.servicio="modificar";
    console.log("nuevo(en el servicio): ", nuevo);

    return this.http.post<Persona>(this.url, nuevo);
   }

   borrar(id:number){
    let p= {
      servicio: "borrar",
      id: id
    }
    console.log("p: ", p);
    return this.http.post<Array<Persona>>(this.url, p);
   }
  }
