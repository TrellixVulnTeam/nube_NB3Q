import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";
import { Pettype } from "../models/petType";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: 'root'
})
export class PetTypeService {

  private url = environment.url_api;

  constructor(private http:HttpClient) { }

  getPetTypes(){
    let peticion = JSON.stringify({
      accion: "ListarPettypes"
    });
    return this.http.post<Pettype[]>(this.url,peticion);
  }
}
