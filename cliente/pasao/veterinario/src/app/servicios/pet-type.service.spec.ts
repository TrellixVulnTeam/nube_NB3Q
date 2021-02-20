import { TestBed } from '@angular/core/testing';

import { PetTypeService } from './pet-type.service';

describe('PetTypeService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: PetTypeService = TestBed.get(PetTypeService);
    expect(service).toBeTruthy();
  });
});
