import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";
import { Specialty } from "../models/specialty";

@Injectable({
  providedIn: 'root'
})
export class SpecialtyService {

  private url = "http://localhost/ANGULAR/petClinic/API/petclinic/servicios.php";

  constructor(private http: HttpClient) { }

  getSpecialties() {
    let peticion = JSON.stringify({
      accion: "ListarSpecialties"
    });
    return this.http.post<Specialty[]>(this.url, peticion);
  }

}
