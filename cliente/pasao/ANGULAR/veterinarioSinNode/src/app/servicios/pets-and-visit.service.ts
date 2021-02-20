import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";
import { environment } from "../../environments/environment";
import { Pet } from '../models/pet';

@Injectable({
  providedIn: 'root'
})
export class PetsAndVisitService {

  private url = environment.url_api;

  constructor(private http:HttpClient) { }

  getPetsByOwner(iden:any){
    let peticion = JSON.stringify({
      accion: "ListarPetsOwnerId",
      id:iden
    });
    return this.http.post<Pet[]>(this.url,peticion);
  }
}

