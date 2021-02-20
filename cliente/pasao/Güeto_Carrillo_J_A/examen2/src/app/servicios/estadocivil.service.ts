import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";
import { Estadocivil } from "../modelos/estadocivil";

@Injectable({
  providedIn: 'root'
})
export class EstadocivilService {

  private url = "http://localhost/ANGULAR/Servicios/servicios.php";

  constructor(private http: HttpClient) { }

  getEstadosCiviles() {
    let peticion = JSON.stringify({
      accion: 9
    });
    return this.http.post<Estadocivil[]>(this.url, peticion);
  }
  
  getSexos(){
    let peticion = JSON.stringify({
      accion: 5
    });
    return this.http.post<Estadocivil[]>(this.url, peticion);
  }
}
