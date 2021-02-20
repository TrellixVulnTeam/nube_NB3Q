import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";
import { Pet } from "../models/pet";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: 'root'
})
export class PetService {

  private url = environment.url_api;

  constructor(private http:HttpClient) { }

  anadePet(objeto:any){
    let peticion = JSON.stringify({
      accion: "AnadePet",
      pet: objeto
    });
    return this.http.post<Pet>(this.url,peticion);
  }

  delPet(ide:any){
    let peticion = JSON.stringify({
      accion: "BorraPet",
      id: ide
    });
    return this.http.post<Pet>(this.url,peticion);
  }

  modPet(animalito:Pet){
    let peticion = JSON.stringify({
      accion: "ModificaPet",
      pet: animalito
    });
    return this.http.post<Pet>(this.url,peticion);
  }

  getPetsByOwner(iden:any){
    let peticion = JSON.stringify({
      accion: "ListarPetsOwnerId",
      id:iden
    });
    return this.http.post<Pet[]>(this.url,peticion);
  }

  cargaPetId(iden:any){
    let peticion = JSON.stringify({
      accion: "ObtenerPetId",
      id:iden
    });
    return this.http.post<Pet>(this.url,peticion);
  }

}
