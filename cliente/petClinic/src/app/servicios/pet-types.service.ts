import { Pettype } from './../models/pettype';
import { environment } from './../../environments/environment';
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class PetTypesService {
  //private url: string = "http://localhost/cliente/provinciaslocalidades/serviciosWeb/petclinic/servicios.php"
  private url: string = environment.API_URL;

  constructor(private http: HttpClient) { }

  getPetTypes(){
    let p= JSON.stringify({
      accion:"ListarPettypes"
    });
    return this.http.post<Pettype[]>(this.url, p)
  }

  addPetType(pettype: Pettype){
    let p= JSON.stringify({
      accion:"AnadePettype",
      pettype: pettype
    });
    return this.http.post<Pettype>(this.url, p)
  }

  modPetType(pettype: Pettype){
    let p= JSON.stringify({
      accion:"ModificaPettype",
      pettype: pettype
    });
    return this.http.post<{result: string}>(this.url, p)
  }

  delPetType(id: number){
    let p= JSON.stringify({
      accion:"BorraPettype",
      id: id
    });
    return this.http.post<{result: string}>(this.url, p)
  }

}
