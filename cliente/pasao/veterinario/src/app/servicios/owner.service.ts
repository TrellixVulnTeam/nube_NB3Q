import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";
import { Owner } from "../models/owner";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: 'root'
})
export class OwnerService {

  private url = environment.url_api;

  constructor(private http:HttpClient) { }

  getOwners(){
    let peticion = JSON.stringify({
      accion: "ListarOwners"
    });
    return this.http.post<Owner[]>(this.url,peticion);
  }

  getOwnerId(iden){
    let peticion = JSON.stringify({
      accion: "ObtenerOwnerId",
      id: iden
    });
    return this.http.post<Owner>(this.url,peticion);
  }

  anadeOwner(objeto:any){
    let peticion = JSON.stringify({
      accion: "AnadeOwner",
      owner: objeto
    });
    return this.http.post<Owner>(this.url,peticion);
  }

  eliminaOwner(ide:any){
    let peticion = JSON.stringify({
        accion: "BorraOwner",
        id: ide,
        listado:"OK"
      });
      return this.http.post<Owner[]>(this.url,peticion);
  }

  modOwner(objeto:any){
    let peticion = JSON.stringify({
      accion: "ModificaOwner",
      owner: objeto
    });
    return this.http.post<Owner>(this.url,peticion);
  }


  getOwnerIdPets(iden){
    let peticion = JSON.stringify({
      accion: "ObtenerOwnerId_Pets",
      id: iden
    });
    return this.http.post<Owner>(this.url,peticion);
  }

}
