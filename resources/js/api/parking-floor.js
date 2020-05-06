import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'parking/floor',
    method: 'get',
    params: query,
  });
}

export function fetchParkingFloor(id) {
  return request({
    url: 'parking/floor/detail/' + id,
    method: 'get',
  });
}


export function createParkingFloor(data) {
  return request({
    url: 'parking/floor/create',
    method: 'post',
    data,
  });
}

export function updateParkingFloor(id,data) {
  return request({
    url: 'parking/floor/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteParkingFloor(id) {
  return request({
    url: 'parking/floor/delete/' + id,
    method: 'get',
  });
}

