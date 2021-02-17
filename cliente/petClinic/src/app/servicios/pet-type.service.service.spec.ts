import { TestBed } from '@angular/core/testing';

import { PetType.ServiceService } from './pet-type.service.service';

describe('PetType.ServiceService', () => {
  let service: PetType.ServiceService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(PetType.ServiceService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
