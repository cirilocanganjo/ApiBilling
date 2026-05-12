import { useState } from 'react'
import api from '../api/axios'
import { useNavigate } from 'react-router-dom'

export default function Login() {

    const navigate = useNavigate()

    const [email, setEmail] = useState('')
    const [password, setPassword] = useState('')
    const [loading, setLoading] = useState(false)

    async function handleLogin(e) {
        e.preventDefault()

        try {
            setLoading(true)

            const response = await api.post('/login', {
                email,
                password
            })

            localStorage.setItem('token', response.data.token)

            navigate('/brands')

        } catch (error) {
            alert('Erro ao fazer login')
            console.log(error)
        } finally {
            setLoading(false)
        }
    }

    return (
        <div className='min-h-screen flex items-center justify-center bg-gray-100'>

            <form
                onSubmit={handleLogin}
                className='bg-white w-full max-w-sm rounded-xl shadow-lg p-6'
            >

                <h1 className='text-3xl font-bold text-center mb-6'>
                    Login
                </h1>

                <div className='mb-4'>

                    <input
                        type='email'
                        placeholder='Email'
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        className='w-full border border-gray-300 rounded-lg p-3 outline-none focus:ring-2 focus:ring-blue-500'
                    />

                </div>

                <div className='mb-4'>

                    <input
                        type='password'
                        placeholder='Password'
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        className='w-full border border-gray-300 rounded-lg p-3 outline-none focus:ring-2 focus:ring-blue-500'
                    />

                </div>

                <button
                    type='submit'
                    disabled={loading}
                    className='w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700'
                >
                    {loading ? 'A entrar...' : 'Entrar'}
                </button>

            </form>

        </div>
    )
}