import { environment } from './../../environments/environment';
import { Owner } from './../models/owner';
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class OwnersService {

  //private url: string = "http://localhost/cliente/provinciaslocalidades/serviciosWeb/petclinic/servicios.php"
  private url: string = environment.API_URL;

  constructor(private http: HttpClient) { }

  getOwners(){
    let pa= JSON.stringify({
      accion: "ListarOwners"
    });
    //get:
    //return this.http.post<any[]>(this.url);
    //post:
    return this.http.post<Owner[]>(this.url, pa);
  }

  getOwnerId(id:number){
    let pa= JSON.stringify({
      accion: "ObtenerOwnerId",
      id: id
    });

    return this.http.post<Owner>(this.url, pa);
  }

  setOwner(owner:Owner){
    let pa= JSON.stringify({
      accion: "AnadeOwner",
      owner: owner
    });

    return this.http.post<Owner>(this.url, pa);
  }

  updateOwner(owner:Owner){
    let pa= JSON.stringify({
      accion: "ModificaOwner",
      owner: owner
    });

    return this.http.post<Owner>(this.url, pa);
  }

  delOwnerList(id: number){
    let pa= JSON.stringify({
      accion: "BorraOwner",
      id: id,
      listado: "OK"
    });
    console.log("id:", id);
    return this.http.post<Owner[]>(this.url, pa);
  }

  delOwner(id:number){
    let pa= JSON.stringify({
      accion: "BorrarOwner",
      id: id,
      listado: "no"
    });

    return this.http.post<Owner>(this.url, pa);
  }
}
