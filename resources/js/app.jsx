import React, { useState, useEffect } from 'react'
import { createRoot } from 'react-dom/client'
import axios from 'axios'
import '../css/app.css'

const API_BASE = '/api/v1'
const TOKEN_KEY = 'dss404_api_token'

const api = axios.create({ baseURL: API_BASE })

// Request interceptor to attach token
api.interceptors.request.use(cfg => {
  const token = localStorage.getItem(TOKEN_KEY)
  if (token) cfg.headers.Authorization = `Bearer ${token}`
  return cfg
})

// Response interceptor to handle 401
api.interceptors.response.use(
  r => r,
  err => {
    if (err.response && err.response.status === 401) {
      localStorage.removeItem(TOKEN_KEY)
      window.dispatchEvent(new Event('dss404:logout'))
    }
    return Promise.reject(err)
  }
)

function useAuth() {
  const [user, setUser] = useState(null)

  useEffect(() => {
    async function fetchMe() {
      try {
        const res = await api.get('/auth/me')
        setUser(res.data.data)
      } catch (e) {
        setUser(null)
      }
    }

    fetchMe()

    function onLogout() {
      setUser(null)
    }

    window.addEventListener('dss404:logout', onLogout)
    return () => window.removeEventListener('dss404:logout', onLogout)
  }, [])

  return [user, setUser]
}

function App() {
  const [user, setUser] = useAuth()
  const [view, setView] = useState('login')
  const [output, setOutput] = useState('Inicia sesión para probar la API.')

  const login = async (email, password) => {
    try {
      const res = await api.post('/auth/login', { email, password })
      const token = res.data.data.token
      localStorage.setItem(TOKEN_KEY, token)
      setOutput(res.data)
      // fetch me
      const me = await api.get('/auth/me')
      setUser(me.data.data)
    } catch (e) {
      setOutput(e.response ? e.response.data : { message: 'Error de red' })
    }
  }

  const register = async (payload) => {
    try {
      const res = await api.post('/auth/register', payload)
      const token = res.data.data.token
      localStorage.setItem(TOKEN_KEY, token)
      setOutput(res.data)
      const me = await api.get('/auth/me')
      setUser(me.data.data)
      setView('login')
    } catch (e) {
      setOutput(e.response ? e.response.data : { message: 'Error de red' })
    }
  }

  const logout = async () => {
    try {
      await api.post('/auth/logout')
    } catch (e) {
      // ignore
    }
    localStorage.removeItem(TOKEN_KEY)
    setUser(null)
    setOutput({ message: 'Sesión cerrada.' })
  }

  return (
    <div className="app-container">
      <header className="app-header">
        <h1>Proyecto DSS 404 - React Auth</h1>
        <div className={user ? 'app-status active' : 'app-status offline'}>
          {user ? 'Sesión activa' : 'Sin sesión'}
        </div>
      </header>

      <main className="app-main">
        <section className="app-grid">
          <aside className="app-card">
            <AuthBox onLogin={login} onRegister={register} onLogout={logout} view={view} setView={setView} />
          </aside>

          <section className="app-card">
            <h2>Endpoints</h2>
            <p className="muted">Después del login puedes consultar las rutas protegidas.</p>
            <div className="button-group" style={{marginTop:14}}>
              <button className="button" onClick={() => loadResource('usuarios', setOutput)}>Usuarios</button>
              <button className="button" onClick={() => loadResource('categorias', setOutput)}>Categorias</button>
              <button className="button" onClick={() => loadResource('productos', setOutput)}>Productos</button>
              <button className="button" onClick={() => loadResource('pedidos', setOutput)}>Pedidos</button>
              <button className="button" onClick={() => loadResource('items-pedido', setOutput)}>Items</button>
            </div>
          </section>
        </section>

        <section className="app-card">
          <h2>Respuesta</h2>
          <pre className="app-output">{typeof output === 'string' ? output : JSON.stringify(output,null,2)}</pre>
        </section>
      </main>
    </div>
  )
}

function AuthBox({ onLogin, onRegister, onLogout, view, setView }) {
  const [email, setEmail] = useState('admin@tienda.com')
  const [password, setPassword] = useState('admin1234')
  const [nombre, setNombre] = useState('')
  const [apellido, setApellido] = useState('')
  const [regEmail, setRegEmail] = useState('')
  const [regPassword, setRegPassword] = useState('')
  const [regPasswordConfirm, setRegPasswordConfirm] = useState('')

  return (
    <div>
      {view === 'login' && (
        <div className="form-grid">
          <label>Email</label>
          <input className="input" value={email} onChange={e=>setEmail(e.target.value)} />
          <label>Password</label>
          <input className="input" type="password" value={password} onChange={e=>setPassword(e.target.value)} />
          <div className="button-group" style={{marginTop:8}}>
            <button className="button" onClick={() => onLogin(email,password)}>Login</button>
            <button className="button danger" onClick={onLogout}>Logout</button>
          </div>
          <p className="form-note">¿No tienes cuenta? <button type="button" className="link-button" onClick={() => setView('register')}>Regístrate</button></p>
        </div>
      )}

      {view === 'register' && (
        <form onSubmit={e=>{e.preventDefault(); onRegister({ nombre, apellido, email:regEmail, password:regPassword, password_confirmation: regPasswordConfirm })}} className="form-grid">
          <label>Nombre</label>
          <input className="input" value={nombre} onChange={e=>setNombre(e.target.value)} />
          <label>Apellido</label>
          <input className="input" value={apellido} onChange={e=>setApellido(e.target.value)} />
          <label>Email</label>
          <input className="input" value={regEmail} onChange={e=>setRegEmail(e.target.value)} />
          <label>Password</label>
          <input className="input" type="password" value={regPassword} onChange={e=>setRegPassword(e.target.value)} />
          <label>Confirmar Password</label>
          <input className="input" type="password" value={regPasswordConfirm} onChange={e=>setRegPasswordConfirm(e.target.value)} />
          <div className="button-group" style={{marginTop:8}}>
            <button className="button" type="submit">Crear cuenta</button>
            <button className="button secondary" type="button" onClick={() => setView('login')}>Volver a Login</button>
          </div>
        </form>
      )}
    </div>
  )
}

async function loadResource(resource, setOutput) {
  try {
    const res = await api.get(`/${resource}?per_page=5`)
    setOutput(res.data)
  } catch (e) {
    setOutput(e.response ? e.response.data : { message: 'Error de red' })
  }
}

const root = createRoot(document.getElementById('app'))
root.render(<App />)
