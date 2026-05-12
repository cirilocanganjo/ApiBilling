import { BrowserRouter, Routes, Route } from 'react-router-dom'
import Login from './pages/Login'
import Brands from './pages/Brands'

export default function App() {

    return (
        <BrowserRouter>
            <Routes>
                <Route path='/' element={<Login />} />
                <Route path='/brands' element={<Brands />} />
            </Routes>
        </BrowserRouter>
    )
}