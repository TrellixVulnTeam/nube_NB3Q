import { Specialty } from './specialty';

export interface Vet {
    id:number;
    firstName:string;
    lastName:string;
    specialties: Specialty[];
}
