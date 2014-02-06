function uf_completarcerosizquierda(as_cadena,ai_cantidad)
{
  li_n=ai_cantidad-as_cadena.length;
  li_i=0;
  ls_aux=as_cadena;
  for (li_i=1;li_i<=li_n;li_i++)
  {
    ls_aux="0"+ls_aux;
  }
  
  return ls_aux;
}

function replace(s, t, u) {
  /*
  **  Replace a token in a string
  **    s  string to be processed
  **    t  token to be found and removed
  **    u  token to be inserted
  **  returns new String
  */
  i = s.indexOf(t);
  r = "";
  if (i == -1) return s;
  r += s.substring(0,i) + u;
  if ( i + t.length < s.length)
    r += replace(s.substring(i + t.length, s.length), t, u);
  return r;
  }

