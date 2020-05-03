import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'lottery',
    method: 'get',
    params: query,
  });
}

export function fetchLottery(id) {
  return request({
    url: 'lottery/detail/' + id,
    method: 'get',
  });
}


export function createLottery(data) {
  return request({
    url: 'lottery/create',
    method: 'post',
    data,
  });
}

export function updateLottery(id,data) {
  return request({
    url: 'lottery/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteLottery(id) {
  return request({
    url: 'lottery/delete/' + id,
    method: 'get',
  });
}
