import { PetType } from "../../models/pettype";
import { environment } from '../../environments/environment';
import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class PetTypeService {

  private url = environment.API_URL;

  constructor(private http: HttpClient) {

  }

  addPetType(type:PetType){
    let pa = JSON.stringify({
      accion: "AnadePettype",
      pettype: type
    });

    return this.http.post<PetType>(this.url, pa)
  }

  deletePetType(id:number){
    let pa = JSON.stringify({
      accion: "BorraPettype",
      id: id
    });

    return this.http.post<PetType>(this.url, pa)
  }

  /*
  updatePetType(type:PetType){
    let pa = JSON.stringify({
      accion: "AnadePettype",
      pettype: type
    });

    return this.http.post<PetType>(this.url, pa)
  } */
}


