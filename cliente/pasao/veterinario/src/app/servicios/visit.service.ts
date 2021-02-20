import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";
import { environment } from "../../environments/environment";
import { Visit } from '../models/visit';

@Injectable({
  providedIn: 'root'
})
export class VisitService {

  private url = environment.url_api;

  constructor(private http:HttpClient) { }

  anadeVisit(objeto:any){
    let peticion = JSON.stringify({
      accion: "AnadeVisit",
      visit: objeto
    });
    console.log(peticion);
    return this.http.post<Visit>(this.url,peticion);
  }

  delVisit(visitId:any){
    let peticion = JSON.stringify({
      accion: "BorraVisit",
      id: visitId
    });
    console.log(peticion);
    return this.http.post(this.url,peticion);
  }
  
  modVisit(visita:Visit){
    let peticion = JSON.stringify({
      accion: "ModificaVisit",
      visit: visita
    });
    console.log(peticion);
    return this.http.post<Visit>(this.url,peticion);
  }

  getVisitId(visitId:any){
    let peticion = JSON.stringify({
      accion: "ObtenerVisitId",
      id: visitId
    });
    console.log(peticion);
    return this.http.post<Visit>(this.url,peticion);
  }
  

}
