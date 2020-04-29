function array_column(array,key)
{
  let column=[];
  array.forEach((item)=>{
    column.push(item[key]);
  });
  return column;
}
